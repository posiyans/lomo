<?php

namespace App\Modules\Receipt\Validators;

use App\Modules\Billing\Models\BillingInvoiceModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GetReceiptForInvoiceValidator extends FormRequest
{


    public function authorize()
    {
        return Auth::user()->ability('superAdmin', ['invoice-show', 'invoice-edit']);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'invoice_id' => [
                'required',
                'exists:' . BillingInvoiceModel::class . ',id',
            ],
        ];
    }

}