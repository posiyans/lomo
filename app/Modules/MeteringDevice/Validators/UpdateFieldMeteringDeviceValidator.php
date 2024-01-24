<?php

namespace App\Modules\MeteringDevice\Validators;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFieldMeteringDeviceValidator extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'field' => [
                'required',
                Rule::in([
                    'device_brand',
                    'serial_number',
                    'installation_date',
                    'initial_data',
                    'verification_date',
                    'description',
                    'active'
                ])
            ],
            'value' => [
                'nullable'
            ],
        ];
    }

}