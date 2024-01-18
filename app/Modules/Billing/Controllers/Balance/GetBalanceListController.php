<?php

namespace App\Modules\Billing\Controllers\Balance;

use App\Http\Controllers\Controller;
use App\Modules\Billing\Resources\BalanceForSteadResource;
use App\Modules\Billing\Validators\Balance\GetBalansListValidator;
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
            $steads = (new SteadRepository())->findByNumber($request->find)->paginate($limit);
            return BalanceForSteadResource::collection($steads);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }


}
