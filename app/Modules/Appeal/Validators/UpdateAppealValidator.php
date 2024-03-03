<?php

namespace App\Modules\Appeal\Validators;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UpdateAppealValidator extends FormRequest
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
                Rule::in(['appeal_type_id'])
            ],
            'value' => [
                'required'
            ],
        ];
    }

}