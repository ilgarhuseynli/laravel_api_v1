<?php

namespace Modules\Service\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class StoreServiceCategoryRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('service_category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name'    => [
                'max:255',
                'required',
                'unique:service_categories,name',
            ],
            'active'=>[
                Rule::in([0,1]),
                'required'
            ],
        ];
    }
}
