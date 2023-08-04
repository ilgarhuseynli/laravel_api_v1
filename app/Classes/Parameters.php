<?php

namespace App\Classes;


class Parameters
{

    public static function get($key, $keyName = false)
    {
        $data = [];
        if (in_array($key,get_class_methods(self::class))){
            $data = call_user_func("self::" . "" . $key);
        }
        $result = [];
        foreach ($data as $value) {
            $r = [];
            foreach ($value as $k => $v) {
                $r[$k] = $v;
            }

            if ($keyName) {
                $result[(string)$r[$keyName]] = $r;
            } else {
                $result[] = $r;
            }
        }
        return $result;
    }


    public static function payment_status()
    {
        return [
            ["id" => 0, "value" => 'unpaid',"title" => 'Unpaid'],
            ["id" => 1, "value" => 'paid' ,"title" => 'Paid'],
        ];
    }


    public static function currency_list($id = false)
    {
        $list = [
            ['id' => 1, 'value' => 1, 'sign' => '$', 'slug' => 'usd', 'label' => 'USD'],
            ['id' => 2, 'value' => 2, 'sign' => '₼', 'slug' => 'azn', 'label' => 'AZN'],
            ['id' => 3, 'value' => 3, 'sign' => '€', 'slug' => 'eur', 'label' => 'EUR'],
        ];

        return $id ? $list[$id - 1] : $list;
    }

}
