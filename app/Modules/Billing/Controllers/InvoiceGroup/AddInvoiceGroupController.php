<?php

namespace App\Modules\Billing\Controllers\InvoiceGroup;

use App\Http\Controllers\Controller;
use App\Modules\Billing\Actions\Invoice\CreateInvoiceAction;
use App\Modules\Billing\Actions\InvoiceGroup\CreateInvoiceGroupAction;
use App\Modules\Billing\Resources\InvoiceGroupResource;
use App\Modules\Billing\Validators\Invoice\AddInvoiceGroupValidator;
use App\Modules\Stead\Repositories\SteadRepository;
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
            $invoiceGroup = (new CreateInvoiceGroupAction())
                ->title($request->title)
                ->rateGroup($request->rate_group_id)
                ->options($request->rate)
                ->run();
            if ($request->stead_type == 'all') {
                (new SteadRepository())->get()->each(function ($stead) use ($invoiceGroup) {
                    (new CreateInvoiceAction($stead))->byInvoiceGroup($invoiceGroup)->run();
                });
            }
            DB::commit();
            return new InvoiceGroupResource($invoiceGroup);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['errors' => $e->getMessage()], 450);
        }
    }


}
