<?php

namespace App\Modules\MeteringDevice\Validators;

use App\Modules\Rate\Models\RateGroupModel;
use App\Modules\Rate\Models\RateTypeModel;
use App\Modules\Stead\Models\SteadModel;
use Illuminate\Foundation\Http\FormRequest;

class GetInstrumentReadingListValidator extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'page' => [
                'integer'
            ],
            'limit' => [
                'integer'
            ],
            'stead_id' => [
                'nullable',
                'exists:' . SteadModel::class . ',id'
            ],
            'rate_type_id' => [
                'nullable',
                'exists:' . RateTypeModel::class . ',id'
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
            'xlsx' => [
                'nullable',
                'boolean'
            ]
        ];
    }

}