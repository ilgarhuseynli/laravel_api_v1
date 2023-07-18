<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;

class Permission
{

    public static function getServices($key = false){

        $list = [
            'general' => ['title' => 'General','value' => 'general'],
            'order_management' => ['title' => 'Order Management','value' => 'order_management'],
            'product_management' => ['title' => 'Product Management','value' => 'product_management'],
        ];

        return $key ? $list[$key] : $list;
    }

    public static function getGroups($key = false){

        $list = [
            'moderator' => ['title' => 'Moderator','value' => 'moderator'],
            'employee' => ['title' => 'Employee','value' => 'employee'],
            'user' => ['title' => 'Users','value' => 'user'],
            'permission' => ['title' => 'Permissions','value' => 'permission'],
            'order' => ['title' => 'Order','value' => 'order'],
            'product' => ['title' => 'Product','value' => 'product'],
        ];

        return $key ? $list[$key] : $list;
    }


    public static function permissionsList($allowedPerms = false){

        $list = [

            "moderator_view" => [
                "value" => "moderator_view",
                "title" => "View",
                "group" => 'moderator',
                "service" => 'general',
            ],
            "moderator_create" => [
                "value" => "moderator_create",
                "title" => "Create",
                "group" => 'moderator',
                "service" => 'general',
            ],
            "moderator_update" => [
                "value" => "moderator_update",
                "title" => "Update",
                "group" => 'moderator',
                "service" => 'general',
            ],
            "moderator_delete" => [
                "value" => "moderator_delete",
                "title" => "Delete",
                "group" => 'moderator',
                "service" => 'general',
            ],

            "employee_view" => [
                "value" => "employee_view",
                "title" => "View",
                "group" => 'employee',
                "service" => 'general',
            ],
            "employee_create" => [
                "value" => "employee_create",
                "title" => "Create",
                "group" => 'employee',
                "service" => 'general',
            ],
            "employee_update" => [
                "value" => "employee_update",
                "title" => "Update",
                "group" => 'employee',
                "service" => 'general',
            ],
            "employee_delete" => [
                "value" => "employee_delete",
                "title" => "Delete",
                "group" => 'employee',
                "service" => 'general',
            ],

            "user_view" => [
                "value" => "user_view",
                "title" => "View",
                "group" => 'user',
                "service" => 'general',
            ],
            "user_create" => [
                "value" => "user_create",
                "title" => "Create",
                "group" => 'user',
                "service" => 'general',
            ],
            "user_update" => [
                "value" => "user_update",
                "title" => "Update",
                "group" => 'user',
                "service" => 'general',
            ],
            "user_delete" => [
                "value" => "user_delete",
                "title" => "Delete",
                "group" => 'user',
                "service" => 'general',
            ],


            "permission_view" => [
                "value" => "permission_view",
                "title" => "View",
                "group" => 'permission',
                "service" => 'general',
            ],
            "permission_update" => [
                "value" => "permission_update",
                "title" => "Update",
                "group" => 'permission',
                "service" => 'general',
            ],


            "order_view" => [
                "value" => "order_view",
                "title" => "View",
                "group" => 'order',
                "service" => 'order_management',
                "variants" => ['self','all'],
            ],
            "order_create" => [
                "value" => "order_create",
                "title" => "Create",
                "group" => 'order',
                "service" => 'order_management',
                "variants" => ['self','all'],
            ],
            "order_update" => [
                "value" => "order_update",
                "title" => "Update",
                "group" => 'order',
                "service" => 'order_management',
                "variants" => ['self','all'],
            ],
            "order_delete" => [
                "value" => "order_delete",
                "title" => "Delete",
                "group" => 'order',
                "service" => 'order_management',
                "variants" => ['self','all'],
            ],


            "product_view" => [
                "value" => "product_view",
                "title" => "View",
                "group" => 'product',
                "service" => 'product_management',
                "variants" => ['self','all'],
            ],
            "product_create" => [
                "value" => "product_create",
                "title" => "Create",
                "group" => 'product',
                "service" => 'product_management',
                "variants" => ['self','all'],
            ],
            "product_update" => [
                "value" => "product_update",
                "title" => "Update",
                "group" => 'product',
                "service" => 'product_management',
                "variants" => ['self','all'],
            ],
            "product_delete" => [
                "value" => "product_delete",
                "title" => "Delete",
                "group" => 'product',
                "service" => 'product_management',
                "variants" => ['self','all'],
            ],

        ];


        if ($allowedPerms){
            $res = [];
            foreach (self::permissionsList() as $key => $value){
                if (array_key_exists($key,$allowedPerms)){
                    $value['allow'] = $allowedPerms[$key];
                    $res[$key] = $value;
                }
            }
        }else{
            $res = $list;
        }

        return $res;
    }




    public static function getModeratorList() {

        $allowedPerms = [
            'moderator_view' => true,
            'moderator_create' => true,
            'moderator_update' => true,
            'moderator_delete' => true,

            'employee_view' => true,
            'employee_create' => true,
            'employee_update' => true,
            'employee_delete' => true,

            'user_view' => true,
            'user_create' => true,
            'user_update' => true,
            'user_delete' => true,

            'permission_view' => true,
            'permission_update' => true,

            'order_view' => 'all',
            'order_create' => 'all',
            'order_update' => 'all',
            'order_delete' => 'all',

            'product_view' => 'all',
            'product_create' => 'all',
            'product_update' => 'all',
            'product_delete' => 'all',
        ];

        return self::permissionsList($allowedPerms);
    }


    public static function getEmployeeList() {

        $allowedPerms = [
            'order_view' => 'self',
            'order_create' => 'self',
            'order_update' => 'self',
            'order_delete' => 'self',

            'product_view' => 'self',
            'product_create' => 'self',
            'product_update' => 'self',
            'product_delete' => 'self',
        ];

        return self::permissionsList($allowedPerms);
    }


    public static function getUserList() {

        $allowedPerms = [
            'order_view' => 'self',
            'order_create' => 'self',
            'order_update' => 'self',
            'order_delete' => 'self',
        ];

        return self::permissionsList($allowedPerms);
    }



    public static function getByRole($roleId){

        $res = [];
        if ($roleId == Role::MODERATOR){
            $res = self::getModeratorList();
        }elseif($roleId == Role::EMPLOYEE){
            $res = self::getEmployeeList();
        }elseif($roleId == Role::USER){
            $res = self::getUserList();
        }

        return $res;
    }


    public static function formatList($list){

        $data = [];

        foreach ($list as $key => $value){

            if (!$data[$value['service']]){
                $serviceData = self::getServices($value['service']);
                $serviceData['groups'] = [];
                $data[$value['service']] = $serviceData;
            }

            if (!$data[$value['service']]['groups'][$value['group']]){
                $groupData = self::getGroups($value['group']);
                $data[$value['service']]['groups'][$value['group']] = $groupData;
            }

            $currentVal = [
                'title'  => $value['title'],
                'value'  => $value['value'],
                'allow'  => $value['allow'],
                'locked' => $value['locked'] ? : false,
                'variants' => $value['variants'] ? : false,
            ];

            $data[$value['service']]['groups'][$value['group']]['permissions'][$key] = $currentVal;
        }

        return $data;
    }





    public static function check($key,$type = false){
        $permissions = Auth::user()->getPermissions();

        $allow = false;
        if (@$permissions[$key]['allow'])
        {
            $allow = $permissions[$key]['allow'];
        }

        if ($type){
            return $allow === $type;
        }

        return (bool)$allow;
    }


    public static function allSelected(string $permissionName): bool
    {
        return self::check($permissionName,'all');
    }


}
