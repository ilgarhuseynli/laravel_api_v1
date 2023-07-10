<?php

namespace App\Http\Requests;

use App\Classes\Permission;
use App\Models\User;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyUserRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(!Permission::check('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:users,id',
        ];
    }
}
