<?php

namespace App\Http\Requests;

use App\Models\UserOffDays;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Order\Entities\Order;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserOffDayRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('useroffday_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'note' => [
                'nullable',
                'max:600',
            ],
            'status' => [
                'nullable',
                'integer',
                Rule::in(array_keys(UserOffDays::STATUS_SELECT)),
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
