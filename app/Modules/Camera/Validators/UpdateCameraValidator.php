<?php

namespace App\Modules\Camera\Validators;

use App\Modules\BanUser\Repositories\BanUserTypeRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class UpdateCameraValidator extends FormRequest
{

    /**
     * Проверка на создателя галереи или админа.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = Auth::user();
        return $user && $user->ability('superAdmin', ['comment-ban', 'user-ban-edit']);
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
            'type' => [
                'string',
                Rule::in(BanUserTypeRepository::getTypeKey()),

            ],
            'description' => [
                'nullable',
                'string',
                'max:255'
            ],
            'end_ban_time' => [
                'nullable',
                'date_format:Y-m-d H:i'
            ],
            'uid' => [
                'string'
            ]
        ];
    }

    public function attributes()
    {
        return [
            'end_ban_time' => 'Время окончания бана',
            'description' => 'Причина бана',
            'user_uid' => 'Идентификатор пользователя',
        ];
    }

    public function getEndBanTime()
    {
        if ($this->end_ban_time) {
            return date('Y-m-d H:i:s', strtotime($this->end_ban_time));
        }
        return null;
    }


}