<?php

namespace App\Modules\MeteringDevice\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\MeteringDevice\Repositories\InstrumentReadingRepository;
use App\Modules\MeteringDevice\Resources\InstrumentReadingResource;
use App\Modules\MeteringDevice\Validators\GetInstrumentReadingListValidator;
use Illuminate\Support\Facades\Auth;

/**
 * Получить показания приборов
 */
class GetInstrumentReadingListController extends Controller
{

    // 'instrument_reading-edit'
    public function __construct()
    {
        $this->middleware('ability:superAdmin|owner,stead-show');
    }

    public function __invoke(GetInstrumentReadingListValidator $request)
    {
        try {
            $user = Auth::user();
            if (!$user->ability('superAdmin', 'stead-show')) {
                $request->stead_id = $user->owner->steads->map(function ($item) {
                    return $item->id;
                });
            }

            $reading = (new InstrumentReadingRepository())
                ->for_stead($request->stead_id)
                ->for_device($request->device_id)
                ->for_rate_type($request->rate_type_id)
                ->between_date($request->date_start, $request->date_end)
                ->get();
            return response(['status' => true, 'data' => InstrumentReadingResource::collection($reading)]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }

}
