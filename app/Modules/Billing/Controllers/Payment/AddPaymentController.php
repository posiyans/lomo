<?php

namespace App\Modules\Billing\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Modules\Billing\Actions\Payment\AddPaymentAction;
use App\Modules\Billing\Resources\PaymentResource;
use App\Modules\Billing\Validators\Payment\AddPaymentValidator;

/**
 * Добавить платеж
 *
 */
class AddPaymentController extends Controller
{

    public function __invoke(AddPaymentValidator $request)
    {
        try {
            $raw = $request->raw;
            $payment = (new AddPaymentAction())->parseRawData($raw)->run();
            return new PaymentResource($payment);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }


}
