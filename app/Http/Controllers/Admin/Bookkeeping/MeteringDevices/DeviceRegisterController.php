<?php

namespace App\Http\Controllers\Admin\Bookkeeping\MeteringDevices;

use App\Http\Resources\Admin\Bookkeeping\AdminDeviceRegisterResource;
use App\Models\Receipt\DeviceRegisterModel;
use App\Models\Stead;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class DeviceRegisterController extends Controller
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
    public function index(Request $request)
    {
        $query = DeviceRegisterModel::query();
        if ($request->stead_id) {
            $query->where('stead_id', $request->stead_id);
        }
        $devices = $query->paginate($request->limit);
        $data = ['status' =>  true];
        $data['data'] = AdminDeviceRegisterResource::collection($devices);
        return $data;
//        return ['status'=> true, 'data'=>$device, 'total'=>$device->total()];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Добавит прибор для участка
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->stead_id) {
            $stead = Stead::find($request->stead_id);
            if ($stead) {
                $options = $request->all();
                $device = $stead->addMeteringDevice($options);
                if ($device) {
                    return ['status' => true, 'data' => $device];
                }
            }
        }
        return ['status' => false, 'data' => false];
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Обновить прибор
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $device = DeviceRegisterModel::find($id);
        if ($device) {
            $device->fill($request->all());
            if ($device->logAndSave('Изменение', $device->stead_id)) {
                return ['status' => true, 'data' => $device];
            }
        }
        return ['status' => false];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
