<?php

namespace App\Modules\Billing\Validators\Balance;

use App\Modules\Rate\Models\RateGroupModel;
use App\Modules\Stead\Models\SteadModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


class GetBalansListValidator extends FormRequest
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
            'zero_line' => [
                'nullable',
                'numeric'
            ],
            'rate_group_id' => [
                'nullable',
                'exists:' . RateGroupModel::class . ',id'
            ],
            'stead_id' => [
                'nullable',
                'exists:' . SteadModel::class . ',id'
            ],
            'find' => [
                'nullable',
                'string'
            ]
        ];
    }

    public function attributes()
    {
        return [];
    }


}