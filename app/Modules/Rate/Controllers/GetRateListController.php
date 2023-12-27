<?php

namespace App\Modules\Rate\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Rate\Models\RateGroupModel;
use App\Modules\Rate\Resources\RateTypeResource;

/**
 * Получить тарифы для группы
 */
class GetRateListController extends Controller
{

    public function __invoke(RateGroupModel $group)
    {
        try {
            return RateTypeResource::collection($group->rateType);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }

}
