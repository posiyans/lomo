<?php

namespace App\Modules\Owner\Validators;

use App\Modules\Owner\Repositories\OwnerFieldRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateOwnerUserFieldValidator extends FormRequest
{


    public function authorize()
    {
        return Auth::user()->ability('superAdmin', ['owner-edit']);
    }


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
                'string',
                Rule::in((new OwnerFieldRepository())->keys()),
            ],
            'value' => [
                'required'
            ]
        ];
    }

}