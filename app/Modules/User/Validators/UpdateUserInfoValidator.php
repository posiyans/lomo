<?php

namespace App\Modules\User\Validators;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UpdateUserInfoValidator extends FormRequest
{

    /**
     * Проверка на создателя галереи или админа.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = Auth::user();
        if ($user) {
            if ($this->get('user_uid') && $user->ability('superAdmin', 'user-edit')) {
                return true;
            }
            Request::merge(['user_uid' => $user->uid]);
            return true;
        }
        return false;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_uid' => [
                'exists:App\Modules\User\Models\UserModel,uid',
                'required'
            ],
            'last_name' => 'string|max:255',
            'name' => 'string|max:255',
            'middle_name' => 'string|max:255',
            'phone' => 'string|max:255',
            'email' => [
                'email:rfc,dns',
                'unique:App\Modules\User\Models\UserModel',
            ]
        ];
    }


}