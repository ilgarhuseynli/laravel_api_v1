<?php

namespace App\Http\Requests;

use App\Models\User;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
            'full_name' => [
                'required',
            ],
            'address' => [
                'max:255',
                'nullable',
            ],
            'ssn' => [
                'nullable',
                'regex:/[0-9]{3}-[0-9]{2}-[0-9]{4}/',
            ],
            'phone' => [
                'nullable',
                'max:100',
            ],
            'payment_percent' => [
                'nullable',
                'numeric',
                'max:100',
                'min:0',
            ],
            'email' => [
                'required',
                'unique:users,email,NULL,id,deleted_at,NULL',
            ],
            'password' => [
                'required',
                'min:6',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
                'confirmed'
            ],
            'role_id' => [
                'exists:roles,id',
                'required',
            ],
        ];
    }
}
