<?php
namespace App\Http\Controllers\Api\V1\Admin;

use App\Classes\Helpers;
use App\Classes\Res;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Common\Entities\Category;

class MultilistController extends Controller
{
    public function index(Request $request)
    {

        $data      = $request->all();

        $keys      = (array)$data['keys'];
        $key       = (string)$data['key'];
        $filters   = (array)$data['filters'];

        $list = [];

        if(in_array("product_categories", $keys) || $key == "product_categories"){
            $binds = [['type','product'], ['status',1]];
            if (strlen((string)$filters["query"]) > 0)
                $binds[] = ['title','like','%'.(string)$filters["query"].'%'];

            $list["product_categories"] = Helpers::getMinlistData(new Category(),$binds);
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
//            if(strlen(trim((string)$filters["query"])) > 0)
//                $binds["titles.".Lang::$_lang] = ['$regex' => trim(strtolower((string)$filters["query"])), '$options'  => 'i'];
//            $binds["is_deleted"] = ['$ne' => 1];
//            $list["service_categories"] = Helpers::getMinList(new Categories(), $binds, ["parent" => 1]);
//        }
//

//        if(in_array("users", $keys) || $key == "users"){
//            $list["users"] = Corelist::getUsers("user", strlen(trim((string)$filters["query"])) > 0 ? (string)$filters["query"] : "");
//        }
//
//        if(in_array("employees", $keys) || $key == "employees"){
//            $list["employees"] = Corelist::getUsers("employee", strlen(trim((string)$filters["query"])) > 0 ? (string)$filters["query"] : "");
//        }


        return Res::success($key ? $list[$key] : $list);
    }
}
