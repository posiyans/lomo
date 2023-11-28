<?php

namespace App\Modules\Stead\Validators;

use Illuminate\Foundation\Http\FormRequest;


class GetSteadListValidator extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'find' => [
                'string',
                'nullable'
            ],
            'page' => [
                'numeric',
                'nullable'
            ],
            'limit' => [
                'numeric',
                'nullable'
            ],
            'id' => [
//                'exists:App\Modules\Stead\Models\SteadModel,id',
                'numeric'
            ]
        ];
    }


}