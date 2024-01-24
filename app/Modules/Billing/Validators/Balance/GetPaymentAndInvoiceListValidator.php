<?php

namespace App\Modules\Billing\Validators\Balance;

use App\Modules\Rate\Models\RateGroupModel;
use App\Modules\Stead\Models\SteadModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class GetPaymentAndInvoiceListValidator extends FormRequest
{
    public function authorize()
    {
        return Auth::user()->ability('superAdmin', ['payment-edit', 'payment-show', 'invoice-edit', 'invoice-show']);
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
            'rate_group_id' => [
                'nullable',
                'exists:' . RateGroupModel::class . ',id'
            ],
            'date_start' => [
                'nullable',
                'date_format:Y-m-d'
            ],
            'date_end' => [
                'nullable',
                'date_format:Y-m-d'
            ],
            'type' => [
                'nullable',
                Rule::in(['invoice', 'payment'])
            ],
            'is_paid' => [
                'nullable',
                Rule::in(['paid', 'no_paid'])
            ],
            'stead_id' => [
                'required',
                'exists:' . SteadModel::class . ',id'
            ],
            'xlsx' => [
                'nullable',
                'boolean'
            ],
        ];
    }

    public function attributes()
    {
        return [];
    }


}