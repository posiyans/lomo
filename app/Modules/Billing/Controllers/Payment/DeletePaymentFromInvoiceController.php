<?php

namespace App\Modules\Billing\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Modules\Billing\Actions\Payment\DeletePaymentFromInvoiceAction;
use App\Modules\Billing\Models\BillingPaymentModel;

/**
 * Удалить платеж из счета
 *
 */
class DeletePaymentFromInvoiceController extends Controller
{

    public function __invoke(BillingPaymentModel $payment)
    {
        try {
            (new DeletePaymentFromInvoiceAction($payment))->run();
            return response(['status' => true]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }


}
