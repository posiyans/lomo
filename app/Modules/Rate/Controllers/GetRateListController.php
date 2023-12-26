<?php

namespace App\Modules\Rate\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Rate\Models\RateGroupModel;

/**
 * Получить тарифы для группы
 */
class GetRateListController extends Controller
{

    public function __invoke(RateGroupModel $group)
    {
        try {
            return response(['status' => true, 'data' => $group->rateType]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }

}
