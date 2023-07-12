<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        if (!User::checkPermission(request()->route('user')->role_id ,'update'))
            return false;

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
                'nullable',
                'string',
            ],
            'address' => [
                'nullable',
                'string',
            ],
            'username' => [
                'required',
                'unique:users,username,' . $id .',id,deleted_at,NULL',
            ],
            'email' => [
                'required',
                'unique:users,email,' . $id .',id,deleted_at,NULL',
            ],
//            'role_id' => [
//                'required',
//                'integer',
//            ],
        ];
    }
}
