<?php

namespace App\Modules\Billing\Validators\Invoice;

use App\Modules\Rate\Models\RateGroupModel;
use App\Modules\Stead\Models\SteadModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


class GetInvoiceListValidator extends FormRequest
{
    public function authorize()
    {
        return Auth::user()->ability('superAdmin', ['invoice-edit', 'invoice-show']);
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
                'exists:' . RateGroupModel::class . ',id',
            ],
            'is_paid' => [
                'nullable',
                'boolean'
            ],
            'xlsx' => [
                'nullable',
                'boolean'
            ],
            'stead_id' => [
                'nullable',
                'exists:' . SteadModel::class . ',id',
            ]
        ];
    }

    public function attributes()
    {
        return [];
    }


}