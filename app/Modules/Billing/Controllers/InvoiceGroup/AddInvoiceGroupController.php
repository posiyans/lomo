<?php

namespace App\Modules\Billing\Controllers\InvoiceGroup;

use App\Http\Controllers\Controller;
use App\Modules\Billing\Actions\Invoice\CreateInvoiceAction;
use App\Modules\Billing\Actions\InvoiceGroup\CreateInvoiceGroupAction;
use App\Modules\Billing\Resources\InvoiceGroupResource;
use App\Modules\Billing\Validators\Invoice\AddInvoiceGroupValidator;
use App\Modules\MeteringDevice\Repositories\InstrumentReadingRepository;
use App\Modules\Rate\Repositories\RateGroupRepository;
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
            $rate_group = (new RateGroupRepository())->byId($request->rate_group_id);
            if ($rate_group->depends == 1) {
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
            }
            if ($rate_group->depends == 2) {
                $rate_type = collect($request->rate)->map(function ($value) {
                    return $value['id'];
                });
                $devices = (new InstrumentReadingRepository())->forRateType($rate_type->toArray())->get();
                $devices->each(function ($dev) {
                    $dev->metering_device;
                });
                return $devices->groupBy('metering_device.stead_id');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['errors' => $e->getMessage()], 450);
        }
    }


}
