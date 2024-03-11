<?php

namespace App\Modules\Search\Validators;

use Illuminate\Foundation\Http\FormRequest;

class SearchBySiteValidator extends FormRequest
{

//
//    public function authorize()
//    {
//        return Auth::user()->ability('superAdmin', ['rate-edit']);
//    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'find' => [
                'required',
                'string',
            ]
        ];
    }

}