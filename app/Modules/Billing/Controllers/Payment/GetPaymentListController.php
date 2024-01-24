<?php

namespace App\Modules\Billing\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Modules\Billing\Repositories\PaymentRepository;
use App\Modules\Billing\Resources\PaymentResource;
use App\Modules\Billing\Resources\PaymentXlsxFileResource;
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
                ->isError($request->is_error)
                ->forStead($request->stead_id)
                ->forRateGroup($request->rate_group_id)
                ->forDate($request->date_start, $request->date_end);
            if ($request->xlsx) {
                $tmpfname = tempnam(sys_get_temp_dir(), "payment");
                (new PaymentXlsxFileResource())->render($payments->get(), $tmpfname);
                return response()->download($tmpfname, 'Платежи_' . date("Y-m-d_H-i-s") . '.xlsx');
            } else {
                return PaymentResource::collection($payments->paginate($limit));
            }
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }


}
