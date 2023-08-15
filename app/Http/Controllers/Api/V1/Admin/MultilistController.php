<?php
namespace App\Http\Controllers\Api\V1\Admin;

use App\Classes\Helpers;
use App\Classes\Res;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Common\Entities\Category;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderItem;
use Modules\Product\Entities\Product;

class MultilistController extends Controller
{
    public function index(Request $request)
    {

        $data      = $request->all();

        $keys      = (array)$data['keys'];
        $key       = (string)$data['key'];
        $filters   = (array)$data['filters'];
        $query   = (string)$filters['query'];

        $list = [];

        if(in_array("product_positions", $keys)){
            $list["product_positions"] = Product::getPositions();
        }

        if(in_array("order_statuses", $keys)){
            $list["order_statuses"] = Order::getStatusList();
        }

        if(in_array("payment_types", $keys)){
            $list["payment_types"] = Order::getPaymentTypes();
        }

        if(in_array("discount_types", $keys)){
            $list["discount_types"] = OrderItem::getDiscountTypes();
        }


        if(in_array("product_categories", $keys) || $key == "product_categories"){
            $binds = [['type','product'], ['status',1]];
            if (strlen($query) > 0)
                $binds[] = ['title','like','%'.$query.'%'];

            $list["product_categories"] = Helpers::getMinlistData(new Category(),$binds);
        }

        if(in_array("users", $keys) || $key == "users"){
            $binds = [['role_id',User::ROLE_USER]];
            if (strlen($query) > 0)
                $binds[] = ['keyword','like','%'.$query.'%'];

            $list["users"] = Helpers::getMinlistData(new User(),$binds,'name');
        }

        if(in_array("employees", $keys) || $key == "employees"){
            $binds = [['role_id','!=',User::ROLE_USER]];
            if (strlen($query) > 0)
                $binds[] = ['keyword','like','%'.$query.'%'];

            $list["employees"] = Helpers::getMinlistData(new User(),$binds,'name');
        }



//        if(in_array("render_types", $keys)){
//            $list["render_types"] = Attributes::getQuestions();
//        }
//
//        if(in_array("products_statuses", $keys)){
//            $list["products_statuses"] = Helpers::convertObjectToArray(Products::statuses());
//        }
//
//        if(in_array("countries", $keys)){
//            $list["countries"] = Countries::getCountryList("array");
//        }
//
//        if($key == "cities"){
//            $list["cities"] = Countries::getCityList($filters, "array");
//        }


//        if(in_array("service_categories", $keys) || $key == "service_categories"){
//            $binds = ["type" => "service", "data_type" => "file"];
//            if(strlen(trim((string)$filters["titles"])) > 0)
//                $binds["titles.".Lang::$_lang] = ['$regex' => trim(strtolower((string)$filters["titles"])), '$options'  => 'i'];
//            if(strlen(trim($query)) > 0)
//                $binds["titles.".Lang::$_lang] = ['$regex' => trim(strtolower($query)), '$options'  => 'i'];
//            $binds["is_deleted"] = ['$ne' => 1];
//            $list["service_categories"] = Helpers::getMinList(new Categories(), $binds, ["parent" => 1]);
//        }
//


        return Res::success($key ? $list[$key] : $list);
    }
}
