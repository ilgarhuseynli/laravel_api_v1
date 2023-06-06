<?php

namespace Modules\Service\Http\Requests;


use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Service\Entities\Service;
use Symfony\Component\HttpFoundation\Response;

class UpdateServiceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('service_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'title'    => [
                'max:255',
                'required',
                'unique:services,title,' . request()->route('service')->id.',id,deleted_at,NULL',
            ],
            'content' => [
                'required',
            ],
            'icon' => [
                'required',
                Rule::in(array_keys(Service::SERVICE_ICONS)),
            ],
//            'category_id' => [
//                'required',
//                'exists:service_categories,id'
//            ],
            'service_prices.price' => [
                'array'
            ],
            'service_prices.price_type' => [
                'array'
            ],
            'service_prices.service_name' => [
                'array'
            ],
            'service_prices.price.*' => [
                'required_with:service_prices.service_name.*',
                'nullable',
                'not_in:0'
            ],
            'service_prices.service_name.*' => [
                'required_with:service_prices.price.*',
                'max:255',
            ],
            'service_prices.price_type.*' => [
                Rule::in(array_keys(config('panel.price_types'))),
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
