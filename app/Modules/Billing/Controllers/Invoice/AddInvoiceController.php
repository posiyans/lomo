<?php

namespace App\Modules\Billing\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Modules\Billing\Actions\Invoice\CreateInvoiceAction;
use App\Modules\Billing\Models\BillingInvoiceModel;
use App\Modules\Billing\Validators\Invoice\AddInvoiceValidator;
use App\Modules\Stead\Repositories\SteadRepository;

/**
 * Добавить счет участка
 *
 */
class AddInvoiceController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,invoice-edit');
    }

    public function __invoke(BillingInvoiceModel $invoice, AddInvoiceValidator $request)
    {
        try {
            $stead = (new SteadRepository())->byId($request->stead_id);
            $invoice = (new CreateInvoiceAction($stead))
                ->rateGroup($request->rate_group_id)
                ->title($request->title)
                ->price(round($request->price, 2))
                ->description(str_replace("\n", '@', $request->description))
                ->run();

            return response(['status' => true, 'data' => $invoice]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }


}
