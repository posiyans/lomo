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
            $payment = (new AddPaymentAction())
                ->parseRawData($raw)
                ->stead($request->stead_id)
                ->paymentDate($request->date)
                ->price($request->price)
                ->rateGroup($request->rate_group_id)
                ->run();
            return new PaymentResource($payment);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }


}
