<?php

namespace App\Modules\MeteringDevice\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\MeteringDevice\Actions\CreateMeteringDeviceAction;
use App\Modules\MeteringDevice\Resources\MeteringDeviceResource;
use App\Modules\MeteringDevice\Validators\CreateMeteringDeviceValidator;


/**
 * Добавить прибор учета
 */
class CreateMeteringDeviceController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,device-edit');
    }

    public function __invoke(CreateMeteringDeviceValidator $request)
    {
        try {
            $device = (new CreateMeteringDeviceAction($request->stead_id, $request->rate_type_id))
                ->fill($request->validated())
                ->options($request->validated())
                ->run();
            return response(['status' => true, 'data' => new MeteringDeviceResource($device)]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }

}
