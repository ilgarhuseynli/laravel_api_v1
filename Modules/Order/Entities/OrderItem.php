<?php

namespace Modules\Order\Entities;

use App\Classes\Helpers;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Product;

class OrderItem extends Model
{
    public $table = 'order_items';

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
    ];

    protected $fillable = [
        'order_id',
        'title',
        'price',
        'total',
        'total_discount',
        'quantity',
        'product_id',
        'discount_type',
        'discount_value',
        'created_at',
        'updated_at'
    ];

    protected $attributes = [
        'title' => '',
    ];

    const DISCOUNT_TYPE_PERCENT = 'percent';
    const DISCOUNT_TYPE_AMOUNT = 'amount';

    const DISCOUNT_TYPES = [
        self::DISCOUNT_TYPE_PERCENT,
        self::DISCOUNT_TYPE_AMOUNT,
    ];

    public static function getDiscountTypes($val = false){
        $response = [
            ['value' => self::DISCOUNT_TYPE_PERCENT,'label' => 'Percent','sign' => '%', ],
            ['value' => self::DISCOUNT_TYPE_AMOUNT,'label' => 'Amount','sign' => 'AZN',  ],
        ];

        if ($val && $val == self::DISCOUNT_TYPE_PERCENT){
            $response = $response[0];
        }elseif($val && $val == self::DISCOUNT_TYPE_AMOUNT){
            $response = $response[1];
        }

        return $response;
    }


    public function product()
    {
        return $this->belongsTo(Product::class,'product_id')->withTrashed();
    }


    public static function prepareValidRequestData($request)
    {
        $itemsData = $request->validated('items');

        $validRequest = [];

        foreach ($itemsData as $item){

            $item['total'] = Helpers::roundFloatValue($item['price'] * $item['quantity']);

            $item['total_discount'] = 0;

            if ($item['discount_value']){
                if ($item['discount_type'] == self::DISCOUNT_TYPE_AMOUNT){
                    $item['total_discount'] = $item['discount_value'];
                }else if($item['discount_type'] == self::DISCOUNT_TYPE_PERCENT){
                    $item['total_discount'] = ($item['total'] * $item['discount_value']) / 100;
                    $item['total_discount'] = Helpers::roundFloatValue($item['total_discount']);
                }
            }

            //break when error
            if ($item['total_discount'] > $item['total']){
                return Helpers::resError('Discount greather than total',400);
            }

            $item['total'] = $item['total'] - $item['total_discount'];

            $validRequest[] = $item;
        }

        return $validRequest;
    }



}
