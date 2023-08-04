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
            'total'=> $this->total,
            'total_discount'=> $this->total_discount,
            'quantity'=> $this->quantity,
            'product'=> $this->product,
            'discount_type'=> $this->discount_type ? OrderItem::getDiscountTypes($this->discount_type) : false,
            'discount_value'=> $this->discount_value,
            'created_at'=> strtotime($this->created_at),
        ];
    }

}
