<?php

namespace App\Modules\Billing\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Modules\Billing\Actions\Payment\DeletePaymentAction;
use App\Modules\Billing\Models\BillingPaymentModel;

/**
 * Удалить платеж
 *
 */
class DeletePaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('ability:superAdmin,payment-edit');
    }

    public function __invoke(BillingPaymentModel $payment)
    {
        try {
            (new DeletePaymentAction($payment))->run();
            return response(['status' => true]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }


}
