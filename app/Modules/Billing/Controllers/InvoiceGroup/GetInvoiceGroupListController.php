<?php

namespace App\Modules\Billing\Controllers\InvoiceGroup;

use App\Http\Controllers\Controller;
use App\Modules\Billing\Repositories\InvoiceGroupRepository;
use App\Modules\Billing\Resources\InvoiceGroupResource;
use App\Modules\Billing\Validators\Invoice\GetInvoiceGroupListValidator;

/**
 * получить группу счетов
 *
 */
class GetInvoiceGroupListController extends Controller
{

    public function __invoke(GetInvoiceGroupListValidator $request)
    {
        try {
            $invoiceGroups = (new InvoiceGroupRepository())
                ->forRateGroup($request->rate_group_id)
                ->paginate($request->limit);
//            if ($request->xlsx) {
//                $tmpfname = tempnam(sys_get_temp_dir(), "invoice");
//                (new InvoiceXlsxFileResource())->render($invoices->get(), $tmpfname);
//                return response()->download($tmpfname, 'Счета_' . date("Y-m-d_H-i-s") . '.xlsx');
//            } else {
            return InvoiceGroupResource::collection($invoiceGroups);
//            }
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }


}
