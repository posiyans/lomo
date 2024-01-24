<?php

namespace App\Modules\MeteringDevice\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\MeteringDevice\Actions\UpdateMeteringDeviceAction;
use App\Modules\MeteringDevice\Models\MeteringDeviceModel;
use App\Modules\MeteringDevice\Resources\MeteringDeviceResource;
use App\Modules\MeteringDevice\Validators\UpdateFieldMeteringDeviceValidator;


/**
 * Обновить данные прибора учета
 */
class UpdateFieldMeteringDeviceController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,device-edit');
    }

    public function __invoke(MeteringDeviceModel $device, UpdateFieldMeteringDeviceValidator $request)
    {
        try {
            $device = (new UpdateMeteringDeviceAction($device))->field($request->field, $request->value)->run();
            return response(['status' => true, 'data' => new MeteringDeviceResource($device)]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }

}
