<?php

namespace App\Modules\Appeal\Validators;

use Illuminate\Foundation\Http\FormRequest;


class UpdateAppealTypeValidator extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'label' => [
                'nullable',
                'string',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'public' => [
                'nullable',
                'boolean'
            ]
        ];
    }

    public function attributes()
    {
        return [

        ];
    }


}