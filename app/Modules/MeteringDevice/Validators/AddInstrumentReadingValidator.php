<?php

namespace App\Modules\MeteringDevice\Validators;

use App\Modules\Billing\Models\BillingPaymentModel;
use App\Modules\MeteringDevice\Models\MeteringDeviceModel;
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
            'device_id' => [
                'required',
                'exists:' . MeteringDeviceModel::class . ',id'
            ],
            'payment_id' => [
                'nullable',
                'exists:' . BillingPaymentModel::class . ',id'
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