<?php

namespace App\Modules\Rate\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Rate\Repositories\RateGroupRepository;
use Illuminate\Http\Request;

/**
 * Получить список группу тарифов
 */
class GetRateGroupListController extends Controller
{

    public function __invoke(Request $request)
    {
        try {
            $depends = $request->depends;
            $rate_group = (new RateGroupRepository())->depends($depends)->get();
            return response(['status' => true, 'data' => $rate_group]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }

}
