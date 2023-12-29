<?php

namespace App\Modules\Billing\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Modules\Billing\Actions\Invoice\DeleteInvoiceAction;
use App\Modules\Billing\Models\BillingInvoiceModel;

/**
 * Удалить счет
 *
 */
class DeleteInvoiceController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,invoice-edit');
    }

    public function __invoke(BillingInvoiceModel $invoice)
    {
        try {
            (new DeleteInvoiceAction($invoice))->run();
            return response(['status' => true]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }


}
