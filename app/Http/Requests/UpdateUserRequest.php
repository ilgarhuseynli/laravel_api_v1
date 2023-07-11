<?php

namespace App\Http\Requests;

use App\Classes\Permission;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(!Permission::check('user_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        $id = request()->route('user')->id ?? request()->route('user');
        return [
            'name' => [
                'string',
                'required',
            ],
            'surname' => [
                'string',
                'required',
            ],
            'phone' => [
                'string',
                'required',
            ],
            'address' => [
                'string',
                'required',
            ],
            'username' => [
                'required',
                'unique:users,username,' . $id .',id,deleted_at,NULL',
            ],
            'email' => [
                'required',
                'unique:users,email,' . $id .',id,deleted_at,NULL',
            ],
            'role_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
