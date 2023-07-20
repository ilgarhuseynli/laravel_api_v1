<?php

namespace Modules\Common\Http\Requests;

use App\Classes\Permission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Common\Entities\Category;
use Symfony\Component\HttpFoundation\Response;

class StoreCategoryRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(!Permission::check('category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'title'    => [
                'max:255',
                'required',
            ],
            'type'=>[
                Rule::in(Category::TYPE_SELECT),
                'string',
            ],
            'status'=>[
                Rule::in([0,1]),
                'required'
            ],
        ];
    }
}
