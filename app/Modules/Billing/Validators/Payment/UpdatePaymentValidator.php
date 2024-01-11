<?php

namespace App\Modules\Billing\Validators\Payment;

use App\Modules\Rate\Models\RateGroupModel;
use App\Modules\Stead\Models\SteadModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


class UpdatePaymentValidator extends FormRequest
{
    public function authorize()
    {
        return Auth::user()->ability('superAdmin', ['payment-edit']);
    }


    public function rules()
    {
        return [
            'stead_id' => [
                'nullable',
                'exists:' . SteadModel::class . ',id'
            ],
            'description' => [
                'nullable',
                'string'
            ],
            'rate_group_id' => [
                'nullable',
                'exists:' . RateGroupModel::class . ',id'
            ],
            'data_verified' => [
                'nullable',
                'boolean'
            ]
        ];
    }

    public function attributes()
    {
        return [];
    }


}