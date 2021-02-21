<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing\Invoice;

use App\Models\Billing\BillingInvoice;
use App\Models\Billing\BillingPayment;
use App\Models\Stead;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Request;

class MatchPaymentsAndInvoiceController extends Controller
{

    /**
     * Сопоставить платежи и счета по полному совпадению
     */
    public static function FindAndMatch()
    {
        $steads = Stead::all();
        foreach ($steads as $stead) {
            $invoices = BillingInvoice::where('stead_id', $stead->id)->whereNull('payment_id')->get();
            $payments = BillingPayment::where('stead_id', $stead->id)->whereNull('invoice_id')->get();
            foreach ($invoices  as $invoice) {
                foreach ($payments as $payment) {
                    if ($payment->invoice_id == null
                        && $payment->price == $invoice->price
                        && $payment->type == $invoice->type
                    ) {
                        $invoice->match($payment);
                        $invoice->paid = true;
                        $invoice->save();
                    }
                }
            }
        }
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
}
