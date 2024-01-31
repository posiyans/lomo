<?php

namespace App\Modules\Billing\Controllers\Balance;

use App\Http\Controllers\Controller;
use App\Modules\Billing\Repositories\InvoiceRepository;
use App\Modules\Billing\Repositories\PaymentRepository;
use App\Modules\Billing\Resources\PaymentAndInvoiceXlsxFileResource;
use App\Modules\Billing\Validators\Balance\GetPaymentAndInvoiceListValidator;
use App\Modules\MeteringDevice\Resources\InstrumentReadingSmallResource;

/**
 * получить список счетов и платежей
 *
 */
class GetPaymentAndInvoiceListController extends Controller
{

    public function __invoke(GetPaymentAndInvoiceListValidator $request)
    {
        try {
            $limit = $request->limit;
            $page = $request->page;

            $result = collect();
            if (!$request->type || $request->type == 'invoice') {
                $result = $result->merge(
                    (new InvoiceRepository())
                        ->forStead($request->stead_id)
                        ->forRateGroup($request->rate_group_id)
                        ->forDate($request->date_start, $request->date_end)
                        ->get()->each(function ($item, $key) use ($result) {
                            $item->type = [
                                'uid' => 'invoice',
                                'label' => 'Счет',
                            ];
                            $item->stead = [
                                'id' => $item->stead->id,
                                'number' => $item->stead->number,
                                'size' => $item->stead->size,
                            ];
                            $item->date = $item->created_at;
                            $item->payments = $item->payments;
                            $item->sort = strtotime($item->created_at);
                            $item->rate = $item->rate_group ? ['id' => $item->rate_group->id, 'name' => $item->rate_group->name] : null;
                        })
                );
            }

            if (!$request->type || $request->type == 'payment') {
                $result = $result->merge(
                    (new PaymentRepository())
                        ->forStead($request->stead_id)
                        ->forRateGroup($request->rate_group_id)
                        ->forDate($request->date_start, $request->date_end)
                        ->get()->each(function ($item, $key) {
                            $item->type = [
                                'uid' => 'payment',
                                'label' => 'Платеж',
                            ];
                            $item->stead = [
                                'id' => $item->stead->id,
                                'number' => $item->stead->number,
                                'size' => $item->stead->size,
                            ];
                            $item->title = $item->raw_data[4] ?? '';
                            $item->date = $item->payment_date;
                            $item->is_paid = $item->invoice ? $item->invoice->is_paid : false;
                            $item->sort = strtotime($item->payment_date);
                            $item->rate = $item->rate_group ? [
                                'id' => $item->rate_group->id,
                                'name' => $item->rate_group->name,
                                'depends' => $item->rate_group->depends,
                            ] : null;
                            $item->readings = InstrumentReadingSmallResource::collection($item->instrument_readings);
                        })
                );
            }

            $is_paid = $request->is_paid;


            if ($is_paid) {
                $result = $result->filter(function ($value, $key) use ($is_paid) {
                    if ($is_paid == 'paid') {
                        return $value->is_paid === true;
                    } else {
                        return $value->is_paid === false;
                    }
                });
            }
            $result = $result->sortByDesc('sort')->values();


            if ($request->xlsx) {
                $tmpfname = tempnam(sys_get_temp_dir(), "balance_stead");
                (new PaymentAndInvoiceXlsxFileResource())->render($result, $tmpfname);
                return response()->download($tmpfname, 'Баланс_' . date("Y-m-d_H-i-s") . '.xlsx');
            } else {
                return [
                    'data' => $result->forPage($page, $limit)->values(),
                    'meta' => [
                        'total' => $result->count()
                    ]
                ];
            }
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }


}
