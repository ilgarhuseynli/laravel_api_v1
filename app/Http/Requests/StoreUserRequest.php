<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        if (!User::checkPermission(request()->role_id,'create'))
            return false;

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
                'digits_between:10,20',
                'nullable',
                'numeric',
            ],
//            'username' => [
//                'required',
//                'unique:users,username,NULL,id,deleted_at,NULL',
//            ],
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
                Rule::in(array_keys(User::ROLES_LIST)),
                'integer',
                'required'
            ],


            'address_list' => [
                'nullable',
                'array',
            ],
            'address_list.*' => [
                'max:255',
            ],

        ];
    }
}
