<?php

namespace App\Modules\Billing\Controllers\Balance;

use App\Http\Controllers\Controller;
use App\Modules\Billing\Repositories\BalanceRepository;
use App\Modules\Billing\Repositories\PaymentRepository;
use App\Modules\Billing\Resources\BalanceXlsxFileResource;
use App\Modules\Billing\Validators\Balance\GetBalansListValidator;
use App\Modules\Rate\Repositories\RateGroupRepository;
use App\Modules\Stead\Repositories\SteadRepository;

/**
 * получить список общего баланса по участкам
 *
 */
class GetBalanceListController extends Controller
{

    public function __invoke(GetBalansListValidator $request)
    {
        try {
            $limit = $request->limit;
            $page = $request->page;
            $find = $request->find;
            $steads = (new SteadRepository())->findByNumber($find)->run();
            $rate_groups = (new RateGroupRepository())->get();
            $steads->each(function ($item, $key) use ($rate_groups) {
                $item->stead = [
                    'id' => $item->id,
                    'number' => $item->number,
                    'size' => $item->size,
                ];
                $item->last_pay = null;
                $last_pay = (new PaymentRepository())->forStead($item->id)->paginate(1);
                if (count($last_pay) == 1) {
                    $item->last_pay = $last_pay[0];
                }
                $balance = (new BalanceRepository())->forStead($item->id)->getPrice();
                $rates = [];
                foreach ($rate_groups as $group) {
                    $rates[] = [
                        'id' => $group->id,
                        'name' => $group->name,
                        'price' => (new BalanceRepository())
                            ->forStead($item->id)
                            ->forRateGroupId($group->id)
                            ->getPrice(),
                    ];
                }
                $item->price = $balance;
                $item->rates = $rates;
            });
            $duty = $request->duty;
            $zero_line = $request->get('zero_line', 0);
            $rate_group = $request->get('rate_group_id', false);
            if ($duty) {
                $steads = $steads->filter(function ($value, $key) use ($duty, $zero_line, $rate_group) {
                    $price = $value->price;
                    if ($rate_group) {
                        $price = (new BalanceRepository())
                            ->forStead($value->id)
                            ->forRateGroupId($rate_group)
                            ->getPrice();
                    }
                    if ($duty == 2) {
                        return $price >= $zero_line;
                    }
                    if ($duty == 1) {
                        return $price < $zero_line;
                    }
                });
            }
            if ($request->xlsx) {
                $tmpfname = tempnam(sys_get_temp_dir(), "balance");
                $rate_groups = (new RateGroupRepository())->get();
                (new BalanceXlsxFileResource())->setRateGroups($rate_groups)->render($steads, $tmpfname);
                return response()->download($tmpfname, 'Баланс_' . date("Y-m-d_H-i-s") . '.xlsx');
            } else {
                return ['data' => $steads->forPage($page, $limit)->values(), 'meta' => ['total' => $steads->count()]];
            }
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }


}
