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
use Carbon\Carbon;
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
            // взносы (по участку)
            $invoiceGroup = (new CreateInvoiceGroupAction())
                ->title($request->title)
                ->rateGroup($request->rate_group_id)
                ->options($request->rate)
                ->run();
            if ($rate_group->depends == 1) {
                if ($request->stead_type == 'all') {
                    (new SteadRepository())->get()->each(function ($stead) use ($invoiceGroup) {
                        (new CreateInvoiceAction($stead))->byInvoiceGroup($invoiceGroup)->run();
                    });
                }
                DB::commit();
                return new InvoiceGroupResource($invoiceGroup);
            }
            // комуналка (по показаниям  приборов)
            if ($rate_group->depends == 2) {
                $rate_type = collect($request->rate)->map(function ($value) {
                    return $value['id'];
                });
                $invoice_date = $request->invoice_date;
                $date_start = (new Carbon($invoice_date))->startofMonth()->toDateString();
                $date_end = (new Carbon($invoice_date))->addMonth()->startofMonth()->toDateString();
                $readings = (new InstrumentReadingRepository())
                    ->forRateType($rate_type->toArray())
                    ->between_date($date_start, $date_end)
                    ->noInvoice()
                    ->get();

                foreach ($readings->groupBy('metering_device.stead_id') as $stead_id => $groups) {
                    $stead = (new SteadRepository())->byId($stead_id);
                    $invoice = (new CreateInvoiceAction($stead))
                        ->title($groups[0]->metering_device->rate_type->rate_group->name . '.')
                        ->invoiceGroup($invoiceGroup);
                    foreach ($groups as $reading) {
                        $invoice->byInstrumentReading($reading);
                    }
                    //todo Итого!!!!!
                    $invoice->run();
                }
                DB::commit();
                return $readings;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['errors' => $e->getMessage()], 450);
        }
    }


}
