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
            if ($request->type_id) {
                $query->where('device_id', $request->type_id);
            } else {
                if ($request->primaryType) {
                    $types = MeteringDevice::where('type_id', $request->primaryType)
                        ->where('enable', 1)
                        ->pluck('id');
//                return $types;
                    $query->whereIn('device_id', $types);
                }
            }
            $items = $query->orderBy('created_at', 'asc')
                ->paginate($request->limit);
            $rez = ['status' => true];
            $rez['data'] = AdminInstrumentReadingsResource::collection($items);
//        dd($rez);
            return $rez;
        }

    }

    public function info(Request $request)
    {
        return 'info';
    }

    public function addInstrumentReadings($id, Request $request)
    {
        $device_id = $request->device_id;
        $value = $request->value;
        if ($device_id && $value) {
            $device = DeviceRegisterModel::find($device_id);
            if ($device && $device->stead_id == $id) {
                $last = $device->getLastReading();
                if ($last && $last->value > $value && $request->test == true) {
                    return ['status' => true, 'error'=> true, 'data'=>'Предыдущие показания больше теущих! Сохраниить?'];
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
