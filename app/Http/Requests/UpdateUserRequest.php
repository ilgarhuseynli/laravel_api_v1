<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        $id = request()->route('user')->id ?? request()->route('user');
        $validationRules = [
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
            'role_id' => [
                'required',
                'integer',
                'exists:roles,id',
            ],
        ];
        if(request()->change_password == 1){
            $validationRules['password'] = [
                'required',
                'min:6',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
                'confirmed'
            ];
        }
        return  $validationRules;

    }
}
