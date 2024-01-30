<?php

namespace App\Modules\Billing\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Modules\Billing\Actions\Invoice\UpdateInvoiceAction;
use App\Modules\Billing\Models\BillingInvoiceModel;
use App\Modules\Billing\Validators\Invoice\UpdateInvoiceFieldValidator;

/**
 * Изменить поле счета
 *
 */
class UpdateInvoiceFieldController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,invoice-edit');
    }

    public function __invoke(BillingInvoiceModel $invoice, UpdateInvoiceFieldValidator $request)
    {
        try {
            (new UpdateInvoiceAction($invoice))
                ->field($request->field, $request->value)
                ->run();
            return response(['status' => true]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }


}
