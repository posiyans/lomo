<?php

namespace App\Modules\Billing\Validators\Invoice;

use App\Modules\Rate\Models\RateGroupModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class AddInvoiceGroupValidator extends FormRequest
{
    public function authorize()
    {
        return Auth::user()->ability('superAdmin', ['invoice-edit']);
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required'
            ],
            'stead_type' => [
                'string',
                'required',
                Rule::in(['all'])
            ],
            'rate_group_id' => [
                'required',
                'exists:' . RateGroupModel::class . ',id',
            ],
            'rate' => [
                'required',
                'array'
            ],
            'invoice_date' => [
                'required',
                'date_format:Y-m-d'
            ]

        ];
    }

    public function attributes()
    {
        return [];
    }


}