<?php

namespace App\Http\Requests;

use App\Classes\Permission;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(!Permission::check('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
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
                'unique:users,username,NULL,id,deleted_at,NULL',
            ],
            'email' => [
                'required',
                'unique:users,email,NULL,id,deleted_at,NULL',
            ],
            'password' => [
                'required',
                'min:6',
//                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
                'confirmed'
            ],
            'role_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
