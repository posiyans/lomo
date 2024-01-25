<?php

namespace App\Modules\MeteringDevice\Validators;

use App\Modules\MeteringDevice\Models\MeteringDeviceModel;
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
        $device = new MeteringDeviceModel();
        return [
            'field' => [
                'required',
                Rule::in(array_merge($device->getFillable(), $device->getOptions()))
            ],
            'value' => [
                'nullable'
            ],
        ];
    }

}