<?php

namespace Modules\Order\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Order\Entities\OrderItem;

class OrderItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'title'=> $this->title,
            'price'=> $this->price,
            'quantity'=> $this->quantity,
            'total'=> $this->total,
            'total_discount'=> $this->total_discount,
            'discount_type'=> $this->discount_type ? OrderItem::getDiscountTypes($this->discount_type)['value'] : false,
            'discount_value'=> $this->discount_value,
            'product'=> $this->product ? [
                'value' => $this->product->id,
                'label' => $this->product->title,
            ] : false,
            'created_at'=> strtotime($this->created_at),
        ];
    }

}
