<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Communal;

use App\Http\Resources\Admin\Bookkeeping\AdminInstrumentReadingsResource;
use App\Models\Receipt\DeviceRegisterModel;
use App\Models\Receipt\InstrumentReadings;
use App\Models\Receipt\MeteringDevice;
use App\Models\Stead;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class ElectroSteadController extends Controller
{


    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,access-admin-panel');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list($id, Request $request)
    {
        $stead = Stead::find($id);
        if ($stead) {
            $query = InstrumentReadings::where('stead_id', $id);
            if ($request->type_id || $request->primaryType) {
                $types_id = MeteringDevice::getDeviceIdForStead($request->primaryType, $stead->id, $request->type_id);
                $query->whereIn('device_id', $types_id);
            }
            $items = $query->orderBy('created_at', 'desc')
                ->paginate($request->limit);
            $rez = ['status' => true];
            $rez['data'] = AdminInstrumentReadingsResource::collection($items);
            return $rez;
        }
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
                    return ['status' => true, 'error'=> true, 'data'=>'Предыдущие показания больше текущих!'];
                }
                $readding = new InstrumentReadings();
                $readding->stead_id = $device->stead_id;
                $readding->device_id = $device->id;
                $readding->value = $value;
                $readding->created_at = $request->date;
                if ($readding->save()) {
                    return ['status' => true, 'error'=> false, 'data'=>$readding];
                }
            }

        }
        return ['status' => false, 'data'=>'Что то пошло не так'];
    }

}
