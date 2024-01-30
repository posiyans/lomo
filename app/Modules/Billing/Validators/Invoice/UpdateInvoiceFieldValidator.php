<?php

namespace App\Modules\Billing\Validators\Invoice;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UpdateInvoiceFieldValidator extends FormRequest
{

    public function rules()
    {
        return [
            'field' => [
                'string',
                'required',
                Rule::in($this->fieldName())
            ],
            'value' => [
                'required'
            ]
        ];
    }

    public function attributes()
    {
        return [];
    }

    private function fieldName()
    {
        return ['title', 'price', 'is_paid', 'description', 'comment'];
    }


}