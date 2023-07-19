<?php

namespace App\Http\Requests;

use App\Classes\Permission;
use App\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateSettingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(!Permission::check('setting_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'keys'   => 'required|array',
            'keys.key.*' => Rule::in(Setting::ALLOWED_KEYS),
        ];
    }
}
