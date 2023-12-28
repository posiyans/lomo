<?php

namespace App\Modules\Rate\Validators;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateRateTypeValidator extends FormRequest
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
            'description' => [
                'required',
                'string'
            ],
            'rate_group_id' => [
                'required',
                'exists:App\Modules\Rate\Models\RateGroupModel,id'
            ]
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'название',
            'description' => 'назначение платежа',
        ];
    }

}