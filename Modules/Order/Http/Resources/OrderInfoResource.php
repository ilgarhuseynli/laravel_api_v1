<?php

namespace Modules\Order\Http\Resources;

use App\Classes\Parameters;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Order\Entities\Order;

class OrderInfoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'ticket_number' => $this->ticket_number,
            'address' => $this->address,
            'phone' => $this->phone,
            'name' => $this->name,
            'currency' => Parameters::currency_list(config('app.currency')),
            'total_amount' => $this->total_amount,
            'total_discount' => $this->total_discount,
            'total_to_pay' => $this->total_to_pay,
            'note' => $this->note,
            'manager_note' => $this->manager_note,
            'customer' => User::filterResponse($this->customer),

            'status' => $this->status ? Order::getStatusList($this->status) : false,
            'payment_type' => $this->payment_type ? Order::getPaymentTypes($this->payment_type) : false,
            'order_date' => $this->order_date ? date('Y-m-d',strtotime($this->order_date)) : '',
            'completed_at' => $this->completed_at ? strtotime($this->completed_at) : '',
            'created_at' => strtotime($this->created_at),

            'items' => OrderItemResource::collection($this->items),
        ];
    }

}
