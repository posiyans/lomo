<?php

namespace App\Modules\Comment\Validators;

use App\Modules\Comment\Repositories\CommentTypeRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class GetMessagesValidator extends FormRequest
{
    public function rules()
    {
        return [
            'type' => [
                'string',
                'required',
                Rule::in(CommentTypeRepository::getKeys()),
            ],
            'uid' => [
                'string',
                'required'
            ],
            'count' => [
                'boolean'
            ]
        ];
    }

    public function attributes()
    {
        return [
            'type' => 'тип',
            'uid' => 'uid',
        ];
    }


}