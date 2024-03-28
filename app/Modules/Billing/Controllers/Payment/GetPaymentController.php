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
        $this->middleware('ability:superAdmin|owner,payment-show,payment-edit');
    }

    public function __invoke(BillingPaymentModel $payment)
    {
        $user = \Auth::user();
        $permissions = ['payment-edit', 'payment-show', 'invoice-edit', 'invoice-show', 'bookkeeping-show'];
        if (!$user->ability('superAdmin', $permissions) and !$user->owner->steads->where('id', $payment->stead_id)->isNotEmpty()) {
            throw new \Exception('Ошибка доступа');
        }
        return new PaymentResource($payment);
    }


}
