<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;

class Permission
{
    public static function getServices($key = false){

        $list = [
            'general' => ['title' => 'General','value' => 'general'],
            'category_management' => ['title' => 'Category Management','value' => 'category_management'],
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
            'setting' => ['title' => 'Settings','value' => 'setting'],
            'category' => ['title' => 'Category','value' => 'category'],
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
                "service" => 'general',
            ],
            "moderator_create" => [
                "value" => "moderator_create",
                "title" => "Create",
                "service" => 'general',
            ],
            "moderator_update" => [
                "value" => "moderator_update",
                "title" => "Update",
                "service" => 'general',
            ],
            "moderator_delete" => [
                "value" => "moderator_delete",
                "title" => "Delete",
                "service" => 'general',
            ],

            "employee_view" => [
                "value" => "employee_view",
                "title" => "View",
                "service" => 'general',
            ],
            "employee_create" => [
                "value" => "employee_create",
                "title" => "Create",
                "service" => 'general',
            ],
            "employee_update" => [
                "value" => "employee_update",
                "title" => "Update",
                "service" => 'general',
            ],
            "employee_delete" => [
                "value" => "employee_delete",
                "title" => "Delete",
                "service" => 'general',
            ],

            "user_view" => [
                "value" => "user_view",
                "title" => "View",
                "service" => 'general',
            ],
            "user_create" => [
                "value" => "user_create",
                "title" => "Create",
                "service" => 'general',
            ],
            "user_update" => [
                "value" => "user_update",
                "title" => "Update",
                "service" => 'general',
            ],
            "user_delete" => [
                "value" => "user_delete",
                "title" => "Delete",
                "service" => 'general',
            ],

            "permission_view" => [
                "value" => "permission_view",
                "title" => "View",
                "service" => 'general',
            ],
            "permission_update" => [
                "value" => "permission_update",
                "title" => "Update",
                "service" => 'general',
            ],

            "setting_view" => [
                "value" => "setting_view",
                "title" => "View",
                "service" => 'general',
            ],
            "setting_update" => [
                "value" => "setting_update",
                "title" => "Update",
                "service" => 'general',
            ],


            "category_view" => [
                "value" => "category_view",
                "title" => "View",
                "service" => 'category_management',
            ],
            "category_create" => [
                "value" => "category_create",
                "title" => "Create",
                "service" => 'category_management',
            ],
            "category_update" => [
                "value" => "category_update",
                "title" => "Update",
                "service" => 'category_management',
            ],
            "category_delete" => [
                "value" => "category_delete",
                "title" => "Delete",
                "service" => 'category_management',
            ],


            "order_view" => [
                "value" => "order_view",
                "title" => "View",
                "service" => 'order_management',
                "variants" => ['self','all'],
            ],
            "order_create" => [
                "value" => "order_create",
                "title" => "Create",
                "service" => 'order_management',
                "variants" => ['self','all'],
            ],
            "order_update" => [
                "value" => "order_update",
                "title" => "Update",
                "service" => 'order_management',
                "variants" => ['self','all'],
            ],
            "order_delete" => [
                "value" => "order_delete",
                "title" => "Delete",
                "service" => 'order_management',
                "variants" => ['self','all'],
            ],


            "product_view" => [
                "value" => "product_view",
                "title" => "View",
                "service" => 'product_management',
            ],
            "product_create" => [
                "value" => "product_create",
                "title" => "Create",
                "service" => 'product_management',
            ],
            "product_update" => [
                "value" => "product_update",
                "title" => "Update",
                "service" => 'product_management',
            ],
            "product_delete" => [
                "value" => "product_delete",
                "title" => "Delete",
                "service" => 'product_management',
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

            'setting_view' => true,
            'setting_update' => true,

            'category_view' => true,
            'category_create' => true,
            'category_update' => true,
            'category_delete' => true,

            'product_view' => true,
            'product_create' => true,
            'product_update' => true,
            'product_delete' => true,

            'order_view' => 'all',
            'order_create' => 'all',
            'order_update' => 'all',
            'order_delete' => 'all',

        ];

        return self::permissionsList($allowedPerms);
    }


    public static function getEmployeeList() {

        $allowedPerms = [
            'category_view' => false,
            'category_create' => false,
            'category_update' => false,
            'category_delete' => false,

            'order_view' => 'self',
            'order_create' => 'self',
            'order_update' => 'self',
            'order_delete' => 'self',

            'product_view' => false,
            'product_create' => false,
            'product_update' => false,
            'product_delete' => false,
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

            $currentGroup = explode('_',$key)[0];

            if (!$data[$value['service']]){
                $serviceData = self::getServices($value['service']);
                $serviceData['groups'] = [];
                $data[$value['service']] = $serviceData;
            }

            if (!$data[$value['service']]['groups'][$currentGroup]){
                $groupData = self::getGroups($currentGroup);
                $data[$value['service']]['groups'][$currentGroup] = $groupData;
            }

            $currentVal = [
                'title'  => $value['title'],
                'value'  => $value['value'],
                'allow'  => $value['allow'],
                'locked' => $value['locked'] ? : false,
                'variants' => $value['variants'] ? : false,
            ];

            $data[$value['service']]['groups'][$currentGroup]['permissions'][$key] = $currentVal;
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
