<?php

namespace App\Modules\Billing\Validators\Invoice;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


class GetInvoiceGroupListValidator extends FormRequest
{
    public function authorize()
    {
        return Auth::user()->ability('superAdmin', ['invoice-edit']);
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
            ]
        ];
    }

    public function attributes()
    {
        return [];
    }


}