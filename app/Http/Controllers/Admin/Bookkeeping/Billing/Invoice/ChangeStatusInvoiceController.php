<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing\Invoice;

use App\Models\Billing\BillingInvoice;
use App\Models\Billing\BillingPayment;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class ChangeStatusInvoiceController extends Controller
{

    /**
     * проверка на суперадмин или на доступ на редактирование начислений
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,reestr-edit');
    }

    public function addPaymentForInvoice(Request $request)
    {
        $invoice_id = $request->post('invoice_id', false);
        $payment_id = $request->post('payment_id', false);
        if ($invoice_id && $payment_id) {
            $invoice = BillingInvoice::find($invoice_id);
            $payment = BillingPayment::find($payment_id);
            if ($payment && $payment->invoice_id == null && $invoice) {
                if ($invoice->match($payment)) {
                    return ['status' => true];
                }
            }
        }
        return ['status' => false];
    }

    public function deletePaymentForInvoice(Request $request)
    {
        $invoice_id = $request->post('invoice_id', false);
        $payment_id = $request->post('payment_id', false);
        if ($invoice_id && $payment_id) {
            $invoice = BillingInvoice::find($invoice_id);
            $payment = BillingPayment::find($payment_id);
            if ($payment && $invoice && $payment->invoice_id == $invoice->id ) {
                $payment->invoice_id = null;
                if ($payment->logAndSave('Удалили платеж из счета')) {
                    return ['status' => true];
                }
            }
        }
        return ['status' => false];
    }

    public function changeStatus(Request $request)
    {
        $invoice_id = $request->post('invoice_id', false);
        $status = $request->post('status', false);
        if ($invoice_id) {
            $invoice = BillingInvoice::find($invoice_id);
            if ($invoice && $invoice->paid != $status) {
                $invoice->paid = $status;
                if ($invoice->logAndSave('Смена статуса платежа')) {
                    return ['status' => true];
                }
            }
        }
        return ['status' => false];
    }


}
