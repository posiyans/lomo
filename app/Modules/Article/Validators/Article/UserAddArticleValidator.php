<?php

namespace App\Modules\Article\Validators\Article;

use App\Modules\Article\Models\ArticleModel;
use App\Modules\BanUser\Repositories\BanUserRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


class UserAddArticleValidator extends FormRequest
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
            if ($user && $user->email_verified_at) {
                (new BanUserRepository())->forUser($user)->forClass(ArticleModel::class)->noBan();
                if ($user->ability('superAdmin', ['article-edit'])) {
                    return true;
                }
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
            'title' => [
                'required',
                'string',
                'max:255',
                'min:10'
            ],
            'text' => [
                'required',
                'string',
                'min:10'
            ],
            'category_id' => [
                'required',
                'exists:App\Modules\Article\Models\CategoryModel,id,public,1',
            ],
            'uid' => [
                'required',
                'unique:App\Modules\Article\Models\ArticleModel,uid',
            ]
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'заголовок',
            'text' => 'записи',
            'category_id' => 'раздела',
        ];
    }

//    public function messages()
//    {
//        return [
//            'title.min' => 'Поле заголовок должно быть не короче 10 символов',
//            'text.min' => 'Поле записи должно быть не короче 10 символов',
//        ];
//    }


}