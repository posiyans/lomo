<?php

namespace App\Modules\Billing\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Modules\Billing\Actions\Payment\UpdatePaymentAction;
use App\Modules\Billing\Models\BillingPaymentModel;
use App\Modules\Billing\Validators\Payment\UpdatePaymentValidator;

/**
 * Изменить данные платежа
 *
 */
class UpdatePaymentController extends Controller
{

    public function __invoke(BillingPaymentModel $payment, UpdatePaymentValidator $request)
    {
        try {
            $update = new UpdatePaymentAction($payment);
            if ($request->has('stead_id')) {
                $update->setStead($request->stead_id);
            }
            if ($request->has('rate_group_id')) {
                $update->setRateGroup($request->rate_group_id);
            }
            if ($request->has('description')) {
                $update->setDescription($request->description);
            }
            if ($request->data_verified) {
                $update->setDataVerified();
            }
            $update->run();
            return response(['status' => true]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }


}
