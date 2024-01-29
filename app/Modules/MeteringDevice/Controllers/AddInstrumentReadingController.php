<?php

namespace App\Modules\MeteringDevice\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\MeteringDevice\Actions\AddInstrumentReadingAction;
use App\Modules\MeteringDevice\Actions\FillOptionsInstrumentReadingAction;
use App\Modules\MeteringDevice\Repositories\MeteringDeviceRepository;
use App\Modules\MeteringDevice\Resources\InstrumentReadingResource;
use App\Modules\MeteringDevice\Validators\AddInstrumentReadingValidator;
use Illuminate\Support\Facades\Auth;

/**
 * Добавить показания приборов
 */
class AddInstrumentReadingController extends Controller
{

    // 'instrument_reading-edit'
    public function __construct()
    {
        $this->middleware('ability:superAdmin|owner,stead-show');
    }

    public function __invoke(AddInstrumentReadingValidator $request)
    {
        try {
            $user = Auth::user();
            if (!$user->ability('superAdmin', 'stead-show')) {
                $request->stead_id = $user->owner->steads->map(function ($item) {
                    return $item->id;
                });
            }
            $device = (new MeteringDeviceRepository())->forStead($request->stead_id)->getById($request->device_id);

            $last_reading = $device->last_reading();
            if (strtotime($last_reading->date) >= strtotime($request->date)) {
                return response(['errors' => 'В системе есть показания на более поздний период'], 450);
            }
            if ($last_reading->value >= $request->value) {
                return response(['errors' => 'Значение показаний должны быть больше предыдущих'], 450);
            }
            $reading = (new AddInstrumentReadingAction($device))->value($request->value)->date($request->date)->run();
            (new FillOptionsInstrumentReadingAction($reading))->run();
            return response(['status' => true, 'data' => new InstrumentReadingResource($reading)]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }

}
