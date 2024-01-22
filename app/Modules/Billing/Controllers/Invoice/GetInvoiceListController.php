<?php

namespace App\Modules\Billing\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Modules\Billing\Repositories\InvoiceRepository;
use App\Modules\Billing\Resources\InvoiceResource;
use App\Modules\Billing\Resources\InvoiceXlsxFileResource;
use App\Modules\Billing\Validators\Invoice\GetInvoiceListValidator;

/**
 * получить выборку счетов
 *
 */
class GetInvoiceListController extends Controller
{

    public function __invoke(GetInvoiceListValidator $request)
    {
        try {
            $limit = $request->limit;
            $invoices = (new InvoiceRepository())
                ->forStead($request->stead_id)
                ->forRateGroup($request->rate_group_id)
                ->isPaid($request->is_paid);
            if ($request->xlsx) {
                $tmpfname = tempnam(sys_get_temp_dir(), "invoice");
                (new InvoiceXlsxFileResource())->render($invoices->get(), $tmpfname);
                return response()->download($tmpfname, 'Счета_' . date("Y-m-d_H-i-s") . '.xlsx');
            } else {
                return InvoiceResource::collection($invoices->paginate($limit));
            }
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }


}
