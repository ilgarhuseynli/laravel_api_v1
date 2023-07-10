<?php

namespace App\Http\Requests;

use App\Classes\Permission;
use App\Post;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySettingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(!Permission::check('setting_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:settings,id',
        ];
    }
}
