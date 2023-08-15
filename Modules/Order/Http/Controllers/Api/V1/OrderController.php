<?php

namespace Modules\Order\Http\Controllers\Api\V1;

use App\Classes\Res;
use App\Classes\Helpers;
use App\Classes\Permission;
use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use App\Http\Controllers\Controller;
use Modules\Order\Entities\OrderItem;
use Symfony\Component\HttpFoundation\Response;
use Modules\Order\Http\Requests\StoreOrderRequest;
use Modules\Order\Http\Requests\UpdateOrderRequest;
use Modules\Order\Http\Resources\OrderInfoResource;
use Modules\Order\Http\Resources\OrderListResource;


class OrderController extends Controller
{

    public function index(Request $request)
    {
        abort_if(!Permission::check('order_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticket_number = $request->ticket_number;
        $address = $request->address;
        $phone = $request->phone;
        $payment_type = $request->payment_type;
        $status = $request->status;
        $user = $request->user;
        $creator = $request->creator;
        $completed_at = $request->completed_at;
        $order_date = $request->order_date;
        $created_at = $request->created_at;


        $skip = (int)$request->skip;
        $limit = Helpers::manageLimitRequest($request->limit);
        $sort = Helpers::manageSortRequest($request->sort,$request->sort_type,Order::$sortable);


        $orderQuery = Order::with('customer','creator');

        if ($creator)
            $orderQuery->where('creator_id',$creator);

        if ($user)
            $orderQuery->where('customer_id',$user);

        if ($ticket_number)
            $orderQuery->where('ticket_number',$ticket_number);

        if (strlen($phone) > 0)
            $orderQuery->where('phone','like','%'.$phone.'%');

        if (strlen($address) > 0)
            $orderQuery->where('address','like','%'.$address.'%');

        if (strlen($payment_type) > 0)
            $orderQuery->where('payment_type',$payment_type);

        if (strlen($status) > 0)
            $orderQuery->where('status',$status);

        $ordersCount = $orderQuery->count();
        $orders = $orderQuery->orderBy($sort[0],$sort[1])->skip($skip)->take($limit)->get();

        return Res::custom([
            'status'=>'success',
            'data'=> OrderListResource::collection($orders),
            'count'=>$ordersCount,
            'skip'=>$skip,
            'limit'=>$limit,
        ]);
    }

    public function store(StoreOrderRequest $request)
    {
        //prepare items
        $validOrderItems = OrderItem::prepareValidRequestData($request);

        if ($validOrderItems['status'] == 'error'){
            return Res::error($validOrderItems['description'],$validOrderItems['error_code']);
        }

        //prepare order
        $validOrder = Order::prepareValidRequestData($request,$validOrderItems);

        $order = Order::create($validOrder);

        foreach ($validOrderItems as $orderItem){
            $orderItem['order_id'] = $order->id;
            OrderItem::create($orderItem);
        }


        //send mail to moderator


        return Res::success(['id' => $order->id],'Created successfully');
    }

    public function update(UpdateOrderRequest $request,Order $order)
    {
        //prepare items
        $validOrderItems = OrderItem::prepareValidRequestData($request);

        if ($validOrderItems['status'] == 'error'){
            return Res::error($validOrderItems['description'],$validOrderItems['error_code']);
        }

        //prepare order
        $validOrder = Order::prepareValidRequestData($request,$validOrderItems);

        $order->update($validOrder);

        //remove old items
        OrderItem::where('order_id', $order->id)->delete();

        //create new items
        foreach ($validOrderItems as $orderItem){
            $orderItem['order_id'] = $order->id;
            OrderItem::create($orderItem);
        }

        return Res::success(['id' => $order->id],'Updated successfully');
    }

    public function show(Order $order)
    {
        abort_if(!Permission::check('order_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order->with('customer','creator');

        $orderItems = OrderItem::with('product')->where('order_id',$order->id)->get();

        $order->items = $orderItems;

        return Res::success(new OrderInfoResource($order));
    }

    public function destroy(Order $order)
    {
        abort_if(!Permission::check('order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order->delete();

        return Res::success([], "Deleted", "Successfully deleted");
    }

}
