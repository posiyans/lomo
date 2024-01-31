<?php

namespace App\Modules\Receipt\Validators;

use Illuminate\Foundation\Http\FormRequest;

class ChangeFaviconValidator extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'favicon' => [
                'required',
                'file',
                'mimes:png'
            ],
        ];
    }

}