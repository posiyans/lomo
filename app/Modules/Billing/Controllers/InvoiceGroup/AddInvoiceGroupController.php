<?php

namespace App\Modules\Billing\Controllers\InvoiceGroup;

use App\Http\Controllers\Controller;
use App\Modules\Billing\Actions\Invoice\CreateInvoiceAction;
use App\Modules\Billing\Actions\InvoiceGroup\CreateInvoiceGroupAction;
use App\Modules\Billing\Validators\Invoice\AddInvoiceGroupValidator;
use Illuminate\Support\Facades\DB;

/**
 * создать грууппу счетов
 *
 */
class AddInvoiceGroupController extends Controller
{

    public function __invoke(AddInvoiceGroupValidator $request)
    {
        try {
            DB::beginTransaction();
            $options = [
                'rate' => $request->rate,
                'steads' => $request->steads,
                'stead_type' => $request->stead_type,
                'invoice_date' => $request->invoice_date
            ];
            $invoiceGroup = (new CreateInvoiceGroupAction())
                ->title($request->title)
                ->rateGroup($request->rate_group_id)
                ->options($options)
                ->run();
            $steads = $request->steads;
            CreateInvoiceAction::byInvoiceGroup($invoiceGroup, $steads);
            if ($invoiceGroup->invoices->count() == 0) {
                throw new \Exception('Счет на оплату не выставлен');
            }
            DB::commit();
            return response([
                'data' =>
                    [
                        'total' => $invoiceGroup->invoices->count(),
                        'price' => round($invoiceGroup->invoices->sum('price'), 2),
                    ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['errors' => $e->getMessage()], 450);
        }
    }


}
