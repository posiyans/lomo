<?php

namespace App\Modules\Stead\Validators;

use App\Modules\Owner\Models\OwnerUserModel;
use App\Modules\Stead\Models\SteadModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


class UpdateSteadOwnerProportionValidator extends FormRequest
{

    public function authorize()
    {
        return Auth::user()->ability('superAdmin', ['stead-edit']);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'owner_uid' => [
                'required',
                'string',
                'exists:' . OwnerUserModel::class . ',uid'
            ],
            'stead_id' => [
                'required',
                'exists:' . SteadModel::class . ',id'
            ],
            'proportion' => [
                'required',
                'numeric',
                'max:100',
                'min:0'
            ]
        ];
    }


}