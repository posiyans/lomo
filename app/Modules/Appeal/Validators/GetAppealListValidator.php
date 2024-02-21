<?php

namespace App\Modules\Appeal\Validators;

use App\Modules\User\Repositories\GetUserByUidRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class GetAppealListValidator extends FormRequest
{

    /**
     *
     * Предлогаем статью
     *
     * @return bool
     */
    public function authorize()
    {
        try {
            $user = Auth::user();
            if ($user) {
                if ($user->ability('superAdmin', ['appeal-edit', 'appeal-edit'])) {
                    return true;
                }
                Request::merge(['user_uid' => $user->uid]);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
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
            'page' => [
                'required',
                'integer'
            ],
            'limit' => [
                'required',
                'integer'
            ],
            'type' => [
                'integer',
                'nullable',
                'exists:App\Modules\Appeal\Models\AppealTypeModel,id',
            ],
            'status' => [
                'string'
            ],
            'find' => [
                'string',
                'nullable'
            ]
        ];
    }

    public function attributes()
    {
        return [
            'type' => 'Тип',
            'status' => 'статус',
            'find' => 'поиск',
        ];
    }

    public function getUser()
    {
        if ($this->user_uid) {
            return (new GetUserByUidRepository($this->user_uid))->run();
        }
        return '';
    }

//    public function messages()
//    {
//        return [
//            'title.min' => 'Поле заголовок должно быть не короче 10 символов',
//            'text.min' => 'Поле записи должно быть не короче 10 символов',
//        ];
//    }


}