<?php

namespace App\Http\Requests;

use App\Classes\Permission;
use App\Models\Setting;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateSettingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(!Permission::check('setting_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'key' => [
                'required',
                'unique:settings,key,' . request()->route('setting')->id,
            ],
        ];
    }
}
