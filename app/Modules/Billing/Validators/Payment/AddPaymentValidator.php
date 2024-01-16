<?php

namespace App\Modules\Billing\Validators\Payment;

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
        ];
    }

    public function attributes()
    {
        return [];
    }


}