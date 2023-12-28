<?php

namespace App\Modules\Rate\Validators;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateRateGroupValidator extends FormRequest
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
            'name' => [
                'required',
                'string'
            ],
            'depends' => [
                'required',
                Rule::in([1, 2, 3])
            ],
            'auto_invoice' => [
                'required',
                'boolean'
            ],
            'payment_period' => [
                'required',
                'numeric'
            ],
            'unit_name' => [
                'string'
            ],
            'tag' => [
                'array'
            ]
        ];
    }

}