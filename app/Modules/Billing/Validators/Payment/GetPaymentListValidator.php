<?php

namespace App\Modules\Billing\Validators\Payment;

use App\Modules\Rate\Models\RateGroupModel;
use App\Modules\Stead\Models\SteadModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


class GetPaymentListValidator extends FormRequest
{
    public function authorize()
    {
        return Auth::user()->ability('superAdmin', ['payment-edit', 'payment-show']);
    }


    public function rules()
    {
        return [
            'page' => [
                'numeric',
                'required'
            ],
            'limit' => [
                'numeric',
                'required'
            ],
            'is_error' => [
                'nullable',
                'boolean'
            ],
            'xlsx' => [
                'nullable',
                'boolean'
            ],
            'rate_group_id' => [
                'nullable',
                'exists:' . RateGroupModel::class . ',id'
            ],
            'find' => [
                'nullable',
                'string'
            ],
            'stead_id' => [
                'nullable',
                'exists:' . SteadModel::class . ',id'
            ],
            'date_start' => [
                'nullable',
                'date_format:Y-m-d'
            ],
            'date_end' => [
                'nullable',
                'date_format:Y-m-d'
            ]
        ];
    }

    public function attributes()
    {
        return [];
    }


}