<?php

namespace App\Modules\Billing\Validators\Invoice;

use App\Modules\Rate\Models\RateGroupModel;
use App\Modules\Stead\Models\SteadModel;
use Illuminate\Foundation\Http\FormRequest;


class AddInvoiceValidator extends FormRequest
{

    public function rules()
    {
        return [
            'stead_id' => [
                'required',
                'exists:' . SteadModel::class . ',id',
            ],
            'title' => [
                'string',
                'required',
            ],
            'description' => [
                'string'
            ],
            'price' => [
                'numeric',
                'required',
            ],
            'rate_group_id' => [
                'required',
                'exists:' . RateGroupModel::class . ',id',
            ]
        ];
    }


}