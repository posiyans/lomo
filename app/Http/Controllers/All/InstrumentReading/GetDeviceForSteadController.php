<?php

namespace App\Http\Controllers\All\InstrumentReading;

use App\Http\Controllers\Controller;
use App\Models\Gardening;
use App\Models\Stead;
use App\Modules\Receipt\Models\ReceiptTypeModels;
use Illuminate\Http\Request;


class GetDeviceForSteadController extends Controller
{


    /**
     * получить список приборов по участку для пердачи по ним показаний
     * если приборов нет то отдается возможный список приборов
     *
     * @param Request $request
     * @return array|false[]
     */
    public function index(Request $request)
    {
        $stead_id = $request->post('stead_id', false);
        $type = $request->post('type', false);
        if ($stead_id && $type) {
            $stead = Stead::find($stead_id);
            $receipt_type = ReceiptTypeModels::find($type);
            if ($stead && $receipt_type) {
                $devices = $stead->getMeteringDevice($receipt_type);
                $rez = [];
                if (count($devices) > 0) {
                    foreach ($devices as $device) {
                        $last = $device->getLastReading();
                        $rez[] = [
                            'id' => 'dev_' . $stead_id . '_' . $device->id,
                            'type' => $device->MeteringDevice->id,
                            'name' => $device->MeteringDevice->name,
                            'description' => $device->MeteringDevice->description,
                            'last' => 0
//                            'last' => $last->value // todo подумать!!!
                        ];
                    }
                } else {
                    $devices = $receipt_type->MeteringDevice;
                    foreach ($devices as $device) {
                        $rez[] = [
                            'id' => 'new_' . $stead_id . '_' . $device->id,
                            'type' => $device->id,
                            'name' => $device->name,
                            'description' => $device->description,
                            'last' => 0 // todo подумать!!!
                        ];
                    }
                }
                return ['status' => true, 'data' => $rez];
            }
        }
        return ['status' => false];
    }

}
