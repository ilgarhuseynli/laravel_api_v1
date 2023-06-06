<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreUserOffDayRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('useroffday_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'user_id' => [
                'exists:users,id',
                'integer',
                'nullable',
            ],
            'note' => [
                'nullable',
                'max:600',
            ],
            'start_at' => [
                'required',
                'date_format:Y-m-d H:i:s'
            ],
            'end_at' => [
                'required',
                'after_or_equal:start_at',
                'date_format:Y-m-d H:i:s'
            ],
        ];
    }
}
