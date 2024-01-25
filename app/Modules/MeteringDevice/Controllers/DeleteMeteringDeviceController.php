<?php

namespace App\Modules\MeteringDevice\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\MeteringDevice\Models\MeteringDeviceModel;


/**
 * Удалить прибор учета
 */
class DeleteMeteringDeviceController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,device-edit');
    }

    public function __invoke(MeteringDeviceModel $device)
    {
        try {
            if ($device->last_reading()) {
                return response(['errors' => 'Нельзя удалить прибор если по нему есть показания'], 450);
            }
            $device->logAndDelete('Удалниеприбора учета');
            return response(['status' => true]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }

}
