<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Classes\Parameters;
use App\Classes\Res;
use App\Classes\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ParametersController extends Controller
{
    public function index(Request $request)
    {
        $parametrName = (string)$request->name;
        $parametrNames = (array)$request->names;

        $response = [];

        if ($parametrName){
            $response[$parametrName] = Parameters::get($parametrName);
        }

        foreach ($parametrNames as $name){
            $response[$name] = Parameters::get($name);
        }


        if (in_array('roles',$parametrNames) || $parametrName == 'roles'){
            $response['roles'] = Role::getList();
        }


        if ($parametrName){
            $response = $response[$parametrName];
        }

        return Res::success($response);
    }
}
