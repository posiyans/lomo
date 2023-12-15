<?php

namespace App\Modules\Article\Validators\Article;

use App\Modules\Article\Actions\AllowPublicationArticleAction;
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
            (new AllowPublicationArticleAction())->run($user);
            return true;
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