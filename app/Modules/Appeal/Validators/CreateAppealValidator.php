<?php

namespace App\Modules\Appeal\Validators;

use App\Modules\User\Repositories\GetUserByUidRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


class CreateAppealValidator extends FormRequest
{

    public function authorize()
    {
        return Auth::user();
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
                'nullable',
                'exists:App\Modules\User\Models\UserModel,uid',
            ],
            'title' => [
                'required',
                'string',
                'min:10'
            ],
            'text' => [
                'required',
                'string',
                'min:15'
            ],
            'appeal_type_id' => [
                'integer',
                'nullable',
                'exists:App\Modules\Appeal\Modules\AppealTypeModel,id',
            ]
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'Тема обращения',
            'text' => 'Текст обращения',
            'type' => 'Тип',
        ];
    }

    public function getUser()
    {
        if ($this->user_uid) {
            return (new GetUserByUidRepository($this->user_uid))->run();
        }
        return '';
    }


}