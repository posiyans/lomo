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
        $this->middleware('ability:superAdmin,owner-edit');
    }

    public function __invoke(SteadModel $stead)
    {
        try {
            $devices = (new MeteringDeviceRepository())->forStead($stead->id)->get();
            return response(['status' => true, 'data' => MeteringDeviceResource::collection($devices)]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }

}
