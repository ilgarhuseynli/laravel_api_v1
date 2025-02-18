<?php

namespace Modules\Order\Http\Requests;

use App\Classes\Permission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderItem;
use Symfony\Component\HttpFoundation\Response;

class StoreOrderRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(!Permission::check('order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'address' => [
                'max:255',
                'required',
            ],
            'name' => [
                'max:255',
                'required',
            ],
            'phone' => [
                'digits_between:10,20',
                'required',
                'numeric',
            ],
            'payment_type' => [
                Rule::in(array_keys(Order::PAYMENT_TYPES)),
                'required',
            ],
            'status' => [
                Rule::in(array_keys(Order::STATUS_LIST)),
                'required'
            ],
            'customer_id' => [
                'nullable',
                'exists:users,id'
            ],
            'order_date' => [
                'required',
                'date',
                'after_or_equal:today',
                'date_format:Y-m-d'
            ],
            'note' => [
                'max:300',
            ],
            'manager_note' => [
                'max:300',
            ],


            'items' => [
                'required',
                'array',
            ],
            'items.*.title' => [
                'max:255',
                'nullable',
            ],
            'items.*.price' => [
                'numeric',
                'min:0'
            ],
             'items.*.quantity' => [
                'numeric',
                'min:1'
            ],
            'items.*.discount_type' => [
                Rule::in(OrderItem::DISCOUNT_TYPES),
                'required_with:items.*.discount_value',
                'nullable',
            ],
            'items.*.discount_value' => [
                'required_with:items.*.discount_type',
                'nullable',
                'numeric',
                'min:0'
            ],
             'items.*.product_id' => [
                 'required',
                 'exists:products,id'
            ],
        ];
    }
}
