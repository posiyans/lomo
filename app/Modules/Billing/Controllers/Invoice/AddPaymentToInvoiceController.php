<?php

namespace App\Modules\Billing\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Modules\Billing\Actions\Invoice\AddPaymentToInvoiceAction;
use App\Modules\Billing\Models\BillingInvoiceModel;
use App\Modules\Billing\Repositories\PaymentRepository;
use Illuminate\Http\Request;

/**
 * Добавить платеж для счета
 *
 */
class AddPaymentToInvoiceController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,invoice-edit');
    }

    public function __invoke(BillingInvoiceModel $invoice, Request $request)
    {
        try {
            $payment = (new PaymentRepository())->byId($request->payment_id);
            if ($payment->invoice) {
                throw new \Exception('Платеж уже прикреплен к счету');
            }
            (new AddPaymentToInvoiceAction($invoice, $payment))->run();
            return response(['status' => true]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }


}
