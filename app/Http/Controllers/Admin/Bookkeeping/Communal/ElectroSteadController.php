<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Communal;

use App\Http\Resources\Admin\Bookkeeping\AdminInstrumentReadingsResource;
use App\Models\Receipt\InstrumentReadings;
use App\Models\Receipt\MeteringDevice;
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
        $query = InstrumentReadings::where('stead_id', $id);
        if ($request->type_id) {
            $query->where('device_id', $request->type_id);
        } else {
            if ($request->primaryType) {
                $types = MeteringDevice::where('type_id',$request->primaryType)
                    ->where('enable', 1)
                    ->pluck('id');
//                return $types;
                $query->whereIn('device_id', $types);
            }
        }
        $items = $query->orderBy('created_at', 'asc')
            ->paginate($request->limit);
        $rez= ['status' => true];
        $rez['data'] = AdminInstrumentReadingsResource::collection($items);
//        dd($rez);
        return $rez;

    }

    public function info(Request $request)
    {
        return 'info';
    }

}
