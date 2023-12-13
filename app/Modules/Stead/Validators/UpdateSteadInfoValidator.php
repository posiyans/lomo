<?php

namespace App\Modules\Stead\Validators;

use App\Modules\Stead\Repositories\SteadFiledRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class UpdateSteadInfoValidator extends FormRequest
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
            'field' => [
                'required',
                'string',
                Rule::in(SteadFiledRepository::getKeys())
            ],
            'value' => SteadFiledRepository::getRule($this->field)
        ];
    }


}