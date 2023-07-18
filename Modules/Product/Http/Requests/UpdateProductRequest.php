<?php

namespace Modules\Product\Http\Requests;


use App\Classes\Permission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Product\Entities\Product;
use Symfony\Component\HttpFoundation\Response;

class UpdateProductRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(!Permission::check('product_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'title'    => [
                'max:255',
                'required',
            ],
            'sku'    => [
                'max:255',
                'nullable',
            ],
            'description' => [
                'max:1500',
                'nullable',
            ],
            'category_id' => [
                'nullable',
                'exists:categories,id'
            ],
            'price'=>[
                'min:0',
                'integer',
            ],
            'status'=>[
                Rule::in([0,1]),
                'required'
            ],
            'position'=>[
                Rule::in(array_keys(Product::POSITION_SELECT)),
                'nullable'
            ],
        ];
    }
}
