<?php

namespace Modules\Common\Http\Requests;

use App\Classes\Permission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(!Permission::check('category_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'title'    => [
                'max:255',
                'required',
            ],
            'status'=>[
                Rule::in([0,1]),
                'required'
            ],
        ];
    }
}
