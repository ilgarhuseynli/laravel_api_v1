<?php

namespace Modules\Service\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class StoreServicePriceCardRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('service_price_card_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'title'    => [
                'max:255',
                'required',
                'unique:service_price_cards,title,NULL,id,deleted_at,NULL',
            ],
            'description' => [
                'nullable',
            ],
            'category_id' => [
                'required',
                'exists:service_categories,id'
            ],
            'starting_price'=>[
                'required',
                'not_in:0',
                'min:1',
            ],
            'rank'=>[
                'min:1',
                'integer',
            ],
            'active'=>[
                Rule::in([0,1]),
                'required'
            ],
        ];
    }
}
