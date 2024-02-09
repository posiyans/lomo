<?php

namespace App\Modules\MeteringDevice\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\MeteringDevice\Repositories\MeteringDeviceRepository;
use App\Modules\MeteringDevice\Resources\MeteringDeviceResource;
use App\Modules\Stead\Models\SteadModel;

/**
 * Добавить участок собственнику
 */
class GetMeteringDevicesForSteadController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin|owner,access-admin-panel');
    }

    public function __invoke(SteadModel $stead)
    {
        try {
            $user = \Auth::user();
            if ($user->ability(['superAdmin'], ['access-admin-panel']) ||
                $user->owner->steads->where('id', $stead->id)->isNotEmpty()
            ) {
                $devices = (new MeteringDeviceRepository())->forStead($stead->id)->get();
                return response(['status' => true, 'data' => MeteringDeviceResource::collection($devices)]);
            } else {
                throw new \Exception('Ошбка доступа');
            }
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }

}
