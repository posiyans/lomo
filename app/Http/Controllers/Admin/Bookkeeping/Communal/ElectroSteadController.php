<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Communal;

use App\Http\Controllers\Controller;
use App\Modules\MeteringDevice\Models\InstrumentReadingModel;
use App\Modules\Receipt\Models\DeviceRegisterModel;
use Illuminate\Http\Request;

class ElectroSteadController extends Controller
{


    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,access-admin-panel');
    }


    public function info(Request $request)
    {
        return 'info';
    }

    /**
     * добавить показания прибора
     *
     * @param $id участка дял проверки
     * @param Request $request
     * @return array
     */
    public function addInstrumentReadings($id, Request $request)
    {
        $device_id = $request->device_id;
        $value = $request->value;
        if ($device_id && $value) {
            $device = DeviceRegisterModel::find($device_id);
            if ($device && $device->stead_id == $id) {
                $last = $device->getLastReading();
                if ($last && $last->value > $value) {
                    return ['status' => true, 'error' => true, 'data' => 'Предыдущие показания больше текущих!'];
                }
                $readding = new InstrumentReadingModel();
                $readding->stead_id = $device->stead_id;
                $readding->device_id = $device->id;
                $readding->value = $value;
                $readding->created_at = $request->date;
                if ($readding->save()) {
                    return ['status' => true, 'error' => false, 'data' => $readding];
                }
            }
        }
        return ['status' => false, 'data' => 'Что то пошло не так'];
    }

}
