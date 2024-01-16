<?php

namespace App\Modules\Billing\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Modules\Billing\Models\BillingPaymentModel;
use App\Modules\Billing\Resources\PaymentResource;

/**
 * Получить данные платежа
 *
 */
class GetPaymentController extends Controller
{


    public function __construct()
    {
        $this->middleware('ability:superAdmin,payment-show,payment-edit');
    }

    public function __invoke(BillingPaymentModel $payment)
    {
        return new PaymentResource($payment);
    }


}
