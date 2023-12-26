<?php

namespace App\Modules\Rate\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Rate\Repositories\RateGroupRepository;

/**
 * Получить список группу тарифов
 */
class GetRateGroupListController extends Controller
{

    public function __invoke()
    {
        try {
            $rate_group = (new RateGroupRepository())->run();
            return response(['status' => true, 'data' => $rate_group]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }

}
