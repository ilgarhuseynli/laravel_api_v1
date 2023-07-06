<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => '1',
                'title' => 'user_management_access',
            ],
//            [
//                'id'    => '2',
//                'title' => 'permission_create',
//            ],
//            [
//                'id'    => '3',
//                'title' => 'permission_edit',
//            ],
//            [
//                'id'    => '4',
//                'title' => 'permission_show',
//            ],
//            [
//                'id'    => '5',
//                'title' => 'permission_delete',
//            ],
//            [
//                'id'    => '6',
//                'title' => 'permission_access',
//            ],
            [
                'id'    => '7',
                'title' => 'role_create',
            ],
            [
                'id'    => '8',
                'title' => 'role_edit',
            ],
            [
                'id'    => '9',
                'title' => 'role_show',
            ],
            [
                'id'    => '10',
                'title' => 'role_delete',
            ],
            [
                'id'    => '11',
                'title' => 'role_access',
            ],
            [
                'id'    => '12',
                'title' => 'user_create',
            ],
            [
                'id'    => '13',
                'title' => 'user_edit',
            ],
            [
                'id'    => '14',
                'title' => 'user_show',
            ],
            [
                'id'    => '15',
                'title' => 'user_delete',
            ],
            [
                'id'    => '16',
                'title' => 'user_access',
            ],
            //comment this perms
//            [
//                'id'    => '38',
//                'title' => 'position_create',
//            ],
//            [
//                'id'    => '39',
//                'title' => 'position_edit',
//            ],
//            [
//                'id'    => '40',
//                'title' => 'position_show',
//            ],
//            [
//                'id'    => '41',
//                'title' => 'position_delete',
//            ],
//            [
//                'id'    => '42',
//                'title' => 'position_access',
//            ],
            [
                'id'         => '53',
                'title'      => 'contact_access',
            ],
            [
                'id'         => '54',
                'title'      => 'contact_show',
            ],
            [
                'id'         => '55',
                'title'      => 'contact_delete',
            ],
            //comment this perm
//            [
//                'id'         => '56',
//                'title'      => 'setting_create',
//            ],
//            [
//                'id'         => '57',
//                'title'      => 'setting_edit',
//            ],
//            [
//                'id'         => '58',
//                'title'      => 'setting_show',
//            ],
            //comment this perm
//            [
//                'id'         => '59',
//                'title'      => 'setting_delete',
//            ],
            [
                'id'         => '88',
                'title'      => 'service_delete',
            ],
            [
                'id'         => '89',
                'title'      => 'service_access',
            ],
            [
                'id'         => '90',
                'title'      => 'service_create',
            ],
            [
                'id'         => '91',
                'title'      => 'service_edit',
            ],
            [
                'id'         => '92',
                'title'      => 'service_show',
            ],
            [
                'id'         => '93',
                'title'      => 'order_delete',
            ],
            [
                'id'         => '94',
                'title'      => 'order_access',
            ],
            [
                'id'         => '95',
                'title'      => 'order_create',
            ],
            [
                'id'         => '96',
                'title'      => 'order_edit',
            ],
            [
                'id'         => '97',
                'title'      => 'order_show',
            ],
            [
                'id'         => '109',
                'title'      => 'order_admin',
            ],


            [
                'id'         => '98',
                'title'      => 'service_managment_access',
            ],
            [
                'id'         => '99',
                'title'      => 'service_category_delete',
            ],
            [
                'id'         => '100',
                'title'      => 'service_category_access',
            ],
            [
                'id'         => '101',
                'title'      => 'service_category_create',
            ],
            [
                'id'         => '102',
                'title'      => 'service_category_edit',
            ],
            [
                'id'         => '103',
                'title'      => 'service_category_show',
            ],
            [
                'id'         => '104',
                'title'      => 'service_price_card_delete',
            ],
            [
                'id'         => '105',
                'title'      => 'service_price_card_access',
            ],
            [
                'id'         => '106',
                'title'      => 'service_price_card_create',
            ],
            [
                'id'         => '107',
                'title'      => 'service_price_card_edit',
            ],
            [
                'id'         => '108',
                'title'      => 'service_price_card_show',
            ],
        ];

        Permission::insert($permissions);
    }
}
