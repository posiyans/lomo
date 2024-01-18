<?php

namespace App\Modules\Billing\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Modules\Billing\Repositories\PaymentRepository;
use App\Modules\Billing\Resources\PaymentResource;
use App\Modules\Billing\Validators\Payment\GetPaymentListValidator;

/**
 * получить список платежей
 *
 */
class GetPaymentListController extends Controller
{

    public function __invoke(GetPaymentListValidator $request)
    {
        try {
            $limit = $request->limit;
            $payments = (new PaymentRepository())
                ->findPayment($request->find)
                ->forRateGroup($request->rate_group_id)
                ->isError($request->is_error)
                ->forRateGroup($request->rate_group_id)
                ->forDate($request->date_start, $request->date_end)
                ->paginate($limit);
            return PaymentResource::collection($payments);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }


}
