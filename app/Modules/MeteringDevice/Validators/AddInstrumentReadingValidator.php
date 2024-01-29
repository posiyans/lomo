<?php

namespace App\Modules\MeteringDevice\Validators;

use App\Modules\MeteringDevice\Models\MeteringDeviceModel;
use App\Modules\Stead\Models\SteadModel;
use Illuminate\Foundation\Http\FormRequest;

class AddInstrumentReadingValidator extends FormRequest
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
                'nullable',
                'exists:' . SteadModel::class . ',id'
            ],
            'device_id' => [
                'required',
                'exists:' . MeteringDeviceModel::class . ',id'
            ],
            'value' => [
                'required',
                'integer'
            ],
            'date' => [
                'required',
                'date_format:Y-m-d'
            ],

        ];
    }

}