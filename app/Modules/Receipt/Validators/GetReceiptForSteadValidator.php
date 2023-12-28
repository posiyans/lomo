<?php

namespace App\Modules\Receipt\Validators;

use App\Modules\Rate\Models\RateGroupModel;
use App\Modules\Stead\Models\SteadModel;
use Illuminate\Foundation\Http\FormRequest;

class GetReceiptForSteadValidator extends FormRequest
{


    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'stead_id' => [
                'required',
                'exists:' . SteadModel::class . ',id',
            ],
            'rate_group_id' => [
                'required',
                'exists:' . RateGroupModel::class . ',id',
            ]
        ];
    }

}