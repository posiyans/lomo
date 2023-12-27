<?php

namespace App\Modules\Rate\Validators;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateRateValidator extends FormRequest
{


    public function authorize()
    {
        return Auth::user()->ability('superAdmin', ['rate-edit']);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ratio_a' => [
                'required',
                'numeric'
            ],
            'ratio_b' => [
                'required',
                'numeric'
            ],
            'description' => [
                'required',
                'string'
            ],
            'date_start' => [
                'required',
                'date_format:Y-m-d'
            ],
        ];
    }

}