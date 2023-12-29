<?php

namespace App\Modules\Billing\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Modules\Billing\Repositories\InvoiceRepository;
use App\Modules\Billing\Resources\InvoiceResource;
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
            $invoices = (new InvoiceRepository())->paginate($limit);
            return InvoiceResource::collection($invoices);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }


}
