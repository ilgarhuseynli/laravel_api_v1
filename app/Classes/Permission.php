<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;

class Permission
{

    public static function permissionsList($allowedPerms = false){

        $list = [

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
            ],
            "order_create" => [
                "value" => "order_create",
                "title" => "Create",
                "group" => 'order',
                "service" => 'order_management',
            ],
            "order_update" => [
                "value" => "order_update",
                "title" => "Update",
                "group" => 'order',
                "service" => 'order_management',
            ],
            "order_delete" => [
                "value" => "order_delete",
                "title" => "Delete",
                "group" => 'order',
                "service" => 'order_management',
            ],

        ];


        if ($allowedPerms){
            $res = [];
            foreach (self::permissionsList() as $key => $value){
                $selectedPerm = $allowedPerms[$key];
                if ($selectedPerm){
                    $value['allow'] = $selectedPerm;
                    $res[$key] = $value;
                }
            }
        }else{
            $res = $list;
        }

        return $res;
    }


    public static function getServices($key = false){

        $list = [
            'general' => ['title' => 'General','value' => 'general'],
            'order_management' => ['title' => 'Order Management','value' => 'order_management'],
        ];

        return $key ? $list[$key] : $list;
    }

    public static function getGroups($key = false){

        $list = [
            'permission' => ['title' => 'Permissions','value' => 'permission'],
            'user' => ['title' => 'Users','value' => 'user'],
            'order' => ['title' => 'Order','value' => 'order'],
        ];

        return $key ? $list[$key] : $list;
    }



    public static function getModeratorList() {

        $allowedPerms = [
            'permission_view' => 'all',
            'permission_update' => 'all',
            'user_view' => 'all',
            'user_create' => 'all',
            'user_update' => 'all',
            'user_delete' => 'all',
            'order_view' => 'all',
            'order_create' => 'all',
            'order_update' => 'all',
            'order_delete' => 'all',
        ];

        return self::permissionsList($allowedPerms);
    }


    public static function getEmployeeList() {

        $allowedPerms = [
            'user_view' => 'self',
            'user_create' => 'self',
            'user_update' => 'self',
            'user_delete' => 'self',
            'order_view' => 'all',
            'order_create' => 'all',
            'order_update' => 'all',
            'order_delete' => 'all',
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
            return $allow == $type;
        }

        return (bool)$allow;
    }


    public static function allSelected(string $permissionName): bool
    {
        return self::check($permissionName,'all');
    }


}
