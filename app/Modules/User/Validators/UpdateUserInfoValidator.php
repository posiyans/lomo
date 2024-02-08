<?php

namespace App\Modules\User\Validators;

use App\Modules\User\Repositories\UserFieldRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


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
            'field' => [
                'required',
                Rule::in(UserFieldRepository::getKeys())
            ],
            'value' => array_merge(['required'], UserFieldRepository::getRules($this->field))
        ];
    }


}