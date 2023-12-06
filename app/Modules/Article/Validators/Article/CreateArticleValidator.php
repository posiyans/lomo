<?php

namespace App\Modules\Article\Validators\Article;

use App\Modules\Article\Repositories\AccessCommentArticleRepository;
use App\Modules\Article\Repositories\StatusArticleRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CreateArticleValidator extends FormRequest
{

    public function authorize()
    {
        return Auth::user()->ability('superAdmin', ['article-edit']);
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
            'resume' => [
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
                'numeric',
                'exists:App\Modules\Article\Models\CategoryModel,id',
            ],
            'status' => [
                'required',
                Rule::in(StatusArticleRepository::getStatusKeys()),
            ],
            'allow_comments' => [
                'required',
                Rule::in(AccessCommentArticleRepository::getAccessKeys()),
            ],
            'slud' => [
                'nullable',
                'string',
                'unique:App\Modules\Article\Models\ArticleModel,slug',
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
            'resume' => 'резюме',
            'text' => 'записи',
            'category_id' => 'категория',
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