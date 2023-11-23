<?php

namespace App\Modules\Stead\Validators;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class GetListOfOwnerSteadValidator extends FormRequest
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
            if ($this->get('user_uid') && $user->ability('superAdmin', ['owner-show', 'stead-show', 'access-admin-panel'])) {
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
            ]
        ];
    }


}