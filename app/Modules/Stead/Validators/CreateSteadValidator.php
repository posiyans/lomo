<?php

namespace App\Modules\Stead\Validators;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


class CreateSteadValidator extends FormRequest
{

    /**
     * Проверка на создателя галереи или админа.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->ability('superAdmin', ['stead-edit']);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'number' => [
                'unique:App\Modules\Stead\Models\SteadModel,number',
                'required'
            ],
            'size' => [
                'numeric'
            ]
        ];
    }


}