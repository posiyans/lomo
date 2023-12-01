<?php

namespace App\Modules\Camera\Validators;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


class CreateCameraValidator extends FormRequest
{

    public function authorize()
    {
        return Auth::user() && Auth::user()->ability('superAdmin', ['camera-edit']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'string',
                'required'
            ],
            'url' => [
                'string',
                'required'
            ],
            'access' => [
                'string',
            ],
            'ttl' => [
                'numeric',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Название',
            'url' => 'RTSP',
            'ttl' => 'Время обновления',
        ];
    }


}