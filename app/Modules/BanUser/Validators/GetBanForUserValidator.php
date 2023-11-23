<?php

namespace App\Modules\BanUser\Validators;

use App\Modules\BanUser\Classes\IsBanUserAdminClass;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


class GetBanForUserValidator extends FormRequest
{

    public function authorize()
    {
        $user = Auth::user();
        if ($user) {
            if ($user->uid == $this->user_uid) {
                return true;
            }
            return $this->isAdmin();
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
            'page' => [
                'integer'
            ],
            'limit' => [
                'integer'
            ],
            'with_trashed' => [
                'boolean'
            ]
        ];
    }

    public function isAdmin()
    {
        $user = Auth::user();
        return IsBanUserAdminClass::isAdmin($user);
    }

}