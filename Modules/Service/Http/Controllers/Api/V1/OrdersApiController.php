<?php

namespace Modules\Order\Http\Controllers\Api\V1;

use App\Classes\Helpers;
use App\Classes\Res;
use App\Classes\Sms;
use App\Contact;
use App\Http\Controllers\Controller;
use App\Mail\NewOrderAdded;
use App\Mail\NewOrderAttachedMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Modules\Message\Entities\Dialogue;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\Report;
use Modules\Order\Http\Requests\StoreOrderRequest;
use Modules\Order\Http\Requests\UpdateOrderRequest;
use Modules\Order\Http\Resources\OrderInfoResource;
use Modules\Order\Http\Resources\OrderListResource;
use Modules\Order\Services\OrderService;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class OrdersApiController extends Controller
{

    public function index(Request $request)
    {
        abort_if(Gate::denies('order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $ordersQuery = Order::orderBy('order_at', 'desc');

        if($request->daterange && strlen($request->daterange) > 10){
            $daterangeElements = explode('-', $request->daterange);
            $daterangeElements[0] = $daterangeElements[0] . ' ' . ' 00:00:00';
            $daterangeElements[1] = $daterangeElements[1] . ' ' . ' 23:59:59';
            $ordersQuery->where('order_at','>=',$daterangeElements[0])
                ->where('order_at','<=',$daterangeElements[1]);
        }

        if ($request->status) {
            if ($request->status == 'completed'){
                $ordersQuery->whereIn('status', [3, 5]);
            }else{
                $ordersQuery->whereNotIn('status', [3, 5]);
            }
        }

        if (Gate::denies('order_admin')) {
            $ordersQuery->leftJoin('order_user', 'orders.id', '=', 'order_user.order_id')
                ->where('order_user.user_id', Auth::id());
        }
        $ordersQuery->select(sprintf('%s.*', (new Order)->table));

        $ordersData = $ordersQuery->paginate(10);

        $allStatuses = Order::STATUS_SELECT;
        $allTypes = Order::TYPE_SELECT;
        $orderUserIds = [];
        foreach ($ordersData as $order) {
            $statusData = $allStatuses[$order->status];
            $statusData['trans'] = trans('cruds.order.status.' . $statusData['value']);
            $order->status =$statusData;

            $typeData = $allTypes[$order->type];
            $typeData['trans'] = trans('cruds.order.type.' . $typeData['value']);
            $order->type = $typeData;

            $orderUserIds = array_merge($orderUserIds,(array)$order->users_json);
        }

        $orderUserIds = array_values(array_unique($orderUserIds));

        $usersByKey = [];
        $users = User::whereIn('id', $orderUserIds)->select(['id', 'name', 'email'])->get();
        foreach ($users as $user) {
            $usersByKey[$user->id] = $user;
        }

        foreach ($ordersData as $ordersDatum){
            $currentUsers = [];
            foreach ((array)$ordersDatum->users_json as $userid)
                if (array_key_exists($userid, $usersByKey)) {
                    $currentUsers[] = $usersByKey[$userid];
                }

            $ordersDatum->technichians = $currentUsers;
        }

        return Res::success(OrderListResource::collection($ordersData));

//        return Res::success($ordersData);
    }


    public function show($id)
    {
        abort_if(Gate::denies('order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order = Order::find($id);

        if (!$order){
            return Res::error('Error','Not found');
        }

        $order->load('appliances', 'users', 'report');

        if (Gate::denies('order_admin')) {
            $myOrder = $order->users->contains(Auth::id());
            if (!$myOrder) {
                abort_if(true, Response::HTTP_FORBIDDEN, '403 Forbidden');
            }
        }

        $users = User::whereIn('id', $order->users_json)->select(['id', 'name'])->get();
        $order->technichians = $users;

//        return new OrderInfoResource($order);

        return Res::success($order);
    }


    public function data(Request $request){

        $response = [];

        $names = $request->names;
        foreach ($names as $name){
            if ($name == 'label'){
                $response['labels'] = Order::STATUS_SELECT;
            } elseif ($name == 'type'){
                $response['types'] = Order::TYPE_SELECT;
            }
        }

        return Res::custom([
            'data' => $response
        ]);
    }

    /**
     * @param StoreOrderRequest $request
     * @return JsonResponse
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        abort_if(Gate::denies('order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $validRequest = Order::prepareValidRequestData($request);

//        if (strtotime($validRequest['order_end_at']) < time()) {
//            return Res::error("Error", "Order date lower than now !!");
//        }

        $userHasOffDay = OrderService::checkEmployeeOffDay($validRequest);

        if ($userHasOffDay) {
            return Res::error('Error', $userHasOffDay['description']);
        }

        $validRequest['creator_percent'] = (float)Auth::user()->payment_percent;
        $validRequest['ticket_num'] = OrderService::getNextTicketNum();

        $order = Order::create($validRequest);

        //insert appliances
        OrderService::orderApplianceAddApi($order->id, $request->appliances);

        $payment_percent = 50;
        if ($request->users) {
            $order->users()->sync($request->input('users', []));

            //send mail to EMPLOYEES
            $users = \App\Models\User::whereIn('id', $request->input('users'))->get();
            $payment_percent = $users->first()->payment_percent;
            Mail::to($users)->send(new NewOrderAttachedMail($order));
        }

        Order::insertOrUpdateImages($order, $request);

        Report::create([
            'order_id' => $order->id,
            'hash' => md5(uniqid(rand(), true)),
            'payment_types' => [0],
            'payment_datas' => [],
            'employee_percent' => $payment_percent > 0 ? $payment_percent : 50,
            'moderator_percent' => Auth::user()->payment_percent ?? 3,
        ]);

        //SEND MAIL TO MODERATOR
        Mail::to(config('panel.moderator_mail'))->send(new NewOrderAdded($order));
//
//        SEND SMS TO CUSTOMER
        $companyPhone = Sms::getCompanyNumber($order->zip);
        $message = Contact::getCustomerMessage('order_added', [
            'company_number' => Helpers::formatUsNumber($companyPhone),
            'customer_name' => $order->customer_name,
            'order_at' => date('m/d/Y', strtotime($order->order_at)),
            'order_time_range' => Order::ORDERAT_RANGE[$order->order_time_range]['title']
        ]);
//
        Dialogue::sendSms($message,$order->customer_phone,$companyPhone);

        if ((int)$request->notify_remote_tech == 1) {
            $receivers = $request->users ?? [];
            OrderService::notifyRemoteTechnicians($order, $receivers);
        }

        return Res::success($order,
            __('global.created'),
            __('global.created_description', ['attribute' => __('cruds.order.title_singular')])
        );
    }

    public function update(UpdateOrderRequest $request, Order $order): JsonResponse
    {
        abort_if(Gate::denies('order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $validRequest = Order::prepareValidRequestData($request);
        $oldOrderStatus = $order->status;
        $oldOrderType = $order->type;
        $dateChanged = date('Y-m-d', strtotime($request->order_at)) != date('Y-m-d', strtotime($order->order_at));

        //if type  order changed check end date greater than now
        if ($request->type != $oldOrderType && strtotime($validRequest['order_end_at']) < time()) {
            return Res::error("Error", "Order date lower than now !!");
        }

        $userHasOffDay = OrderService::checkEmployeeOffDay($validRequest);

        if ($userHasOffDay) {
            return Res::error('Error', $userHasOffDay['description']);
        }

        $order->update($validRequest);

        //appliances delete
        $order->appliances()->delete();
        // appliances add
        OrderService::orderApplianceAddApi($order->id, $request->appliances);

        //USERS ADD REMOVE
        $syncUsers = $order->users()->sync($request->input('users', []));

        //send mail to EMPLOYEES
        if ($syncUsers['attached']) {
            $users = \App\Models\User::whereIn('id', $syncUsers['attached'])->get();
            Mail::to($users)->send(new NewOrderAttachedMail($order));
        }
        if ($syncUsers['detached']) {
            $users = \App\Models\User::whereIn('id', $syncUsers['detached'])->get();
            Mail::to($users)->send(new NewOrderAttachedMail($order, 'detach'));
        }

        //DELETE OR INSERT IMAGES
        Order::insertOrUpdateImages($order, $request, 'update');


        //if type or date of order changed resend sms to cutomer mail to technicians
        if ($order->type != $oldOrderType || $dateChanged) {
            OrderService::resendNotifications($order, $dateChanged, $request->type);
        }

        if ($request->status != $oldOrderStatus) {
            return OrderService::onStatusChange($order, $request->status);
        } else {
            return Res::success(
                $order,
                __('global.updated'),
                __('global.updated_description', ['attribute' => __('cruds.order.title_singular')])
            );
        }
    }

    public function destroy(Order $order): JsonResponse
    {
        abort_if(Gate::denies('order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order->delete();

        $orders = Order::orderBy('order_at', 'desc')->paginate(10);

        return Res::success(
            $orders,
            "Deleted",
            "Successfully deleted"
        );
    }

    public function orderGeometries(Request $request): JsonResponse
    {
        $order_ids = $request->order_ids;
        $orders = Order::orderBy('order_at', 'desc')->whereIn('id', $order_ids)->get([
            "id",
            "address_location",
            "latitude",
            "longitude"
        ]);


        $result = false;

        foreach ($orders as $order) {
            $result[] = Order::getLatLong($order);
        }

        return Res::success($result);
    }

    public function changeStatus()
    {
        $id = request()->has("id") ? request()->id : false;
        $status = request()->has("status") ? request()->status : false;

        if ($id){
            $order = Order::where("id", $id)->first();
            $order->update([
                "status" => $status
            ]);
        }

        return Res::success([]);
    }
}
