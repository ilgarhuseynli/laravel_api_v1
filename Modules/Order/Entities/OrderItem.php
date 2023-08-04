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

    const DISCOUNT_TYPE_PERCENT = 1;
    const DISCOUNT_TYPE_AMOUNT = 2;

    const DISCOUNT_TYPE = [
        self::DISCOUNT_TYPE_PERCENT => 'percent',
        self::DISCOUNT_TYPE_AMOUNT => 'amount',
    ];

    public static function getDiscountTypes($val = false){
        $list = [
            ['value' => self::DISCOUNT_TYPE_PERCENT,'label' => 'Percent', ],
            ['value' => self::DISCOUNT_TYPE_AMOUNT,'label' => 'Amount', ],
        ];

        return $val ? $list[$val - 1] : $list;
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
                }else{
                    $item['total_discount'] = ($item['total'] / $item['discount_value']) / 100;
                    $item['total_discount'] = Helpers::roundFloatValue($item['total_discount']);
                }
            }

            //break when error
            if ($item['total_discount'] > $item['total']){
                return Helpers::resError('Discount greather than total',6030);
            }

            $validRequest[] = $item;
        }

        return $validRequest;
    }



}
