<?php

namespace App\Modules\MeteringDevice\Validators;

use App\Modules\Rate\Models\RateTypeModel;
use App\Modules\Stead\Models\SteadModel;
use Illuminate\Foundation\Http\FormRequest;

class CreateMeteringDeviceValidator extends FormRequest
{

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
                'exists:' . SteadModel::class . ',id'
            ],
            'rate_type_id' => [
                'required',
                'exists:' . RateTypeModel::class . ',id'
            ],
            'initial_data' => [
                'numeric'
            ],
            'description' => [
                'nullable',
                'string'
            ],
            'active' => [
                'boolean'
            ],
            'serial_number' => [
                'nullable',
                'string'
            ],
            'device_brand' => [
                'nullable',
                'string'
            ],
            'installation_date' => [
                'nullable',
                'date_format:Y-m-d'
            ],
            'verification_date' => [
                'nullable',
                'date_format:Y-m-d'
            ]
        ];
    }

}