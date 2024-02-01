<?php

namespace App\Modules\Rate\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Rate\Models\RateGroupModel;
use App\Modules\Rate\Resources\RateTypeResource;
use Illuminate\Http\Request;

/**
 * Получить тарифы для группы
 */
class GetRateListController extends Controller
{


    // todo сделать лалидацию for_date
    public function __invoke(RateGroupModel $group, Request $request)
    {
        try {
            $rate_type = $group->rateType;
            if ($request->for_date) {
                $date = $request->for_date;
                $rate_type->each(function ($value) use ($date) {
                    $value->setCurrentDate($date);
                });
            }
            return RateTypeResource::collection($rate_type);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }

}
