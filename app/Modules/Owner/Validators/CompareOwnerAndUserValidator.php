<?php

namespace App\Modules\Owner\Validators;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CompareOwnerAndUserValidator extends FormRequest
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
            'owner_uid' => [
                'required',
                'exists:App\Modules\Owner\Models\OwnerUserModel,uid',
            ],
            'user_uid' => [
                'required',
                'exists:App\Modules\User\Models\UserModel,uid'
            ]
        ];
    }

}