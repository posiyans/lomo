<?php

namespace App\Modules\Billing\Validators\Payment;

use App\Modules\Rate\Models\RateGroupModel;
use App\Modules\Stead\Models\SteadModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


class AddPaymentValidator extends FormRequest
{
    public function authorize()
    {
        return Auth::user()->ability('superAdmin', ['payment-edit']);
    }


    public function rules()
    {
        return [
            'raw' => [
                'required',
                'array'
            ],
            'stead_id' => [
                'nullable',
                'exists:' . SteadModel::class . ',id',
            ],
            'title' => [
                'nullable',
                'required',
            ],
            'description' => [
                'nullable',
                'string'
            ],
            'price' => [
                'nullable',
                'numeric',
            ],
            'rate_group_id' => [
                'nullable',
                'exists:' . RateGroupModel::class . ',id',
            ]
        ];
    }

    public function attributes()
    {
        return [];
    }


}