<?php

namespace App\Modules\Article\Validators\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ChangeAllowPublicationArticleValidator extends FormRequest
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
            'value' => [
                'required',
                'numeric',
                Rule::in([1, 2, 3]),
            ]
        ];
    }

    public function attributes()
    {
        return [
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