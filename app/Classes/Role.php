<?php

namespace App\Classes;

class Role
{
    const MODERATOR = 1;
    const EMPLOYEE = 2;
    const USER = 3;


    public static function getList(){

        return [
           ['value' => 1,'label' => 'Moderator', ],
           ['value' => 2,'label' => 'Employee', ],
           ['value' => 3,'label' => 'User', ],
        ];
    }


    public static function getById($id){

        $list = [
            1 => ['value' => 1,'label' => 'Moderator'],
            2 => ['value' => 2,'label' => 'Employee'],
            3 => ['value' => 3,'label' => 'User'],
        ];

        return $list[$id];
    }


}
