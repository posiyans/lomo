<?php

namespace App\Http\Controllers;

use App\Models\Gardening;
use App\Models\Stead;
use App\Modules\Receipt\Models\MeteringDevice;
use App\Modules\Receipt\Models\ReceiptTypeModels;
use Illuminate\Http\Request;


class ReceiptController extends Controller
{
    //


    public function index($stread_id = false, Request $request)
    {
        $steadModel = false;
        $receipt = (int)$request->receipt;
        if ($request->isMethod('get')) {
            $ReceiptType = ReceiptTypeModels::all();
            return view('receipt/index', ['stead' => $steadModel, 'receipts' => $ReceiptType]);
        }
        if ($request->isMethod('post')) {
            if (isset($request->stead) and isset($request->receipt)) {
                $stead = $request->stead;
                $ReceiptType = ReceiptTypeModels::findOrFail((int)$request->receipt);
                $steadModel = Stead::where(['number' => $stead])->first();
                if ($ReceiptType) {
                    if ($ReceiptType->depends == 0) {
                    } elseif ($ReceiptType->depends == 1) {
                        $devices = MeteringDevice::where('enable', 1)->where('type_id', $ReceiptType->id)->get();
                        foreach ($devices as $device) {
                            $device->rateNow();
                        }
                        return view('receipt/device1', ['stead' => $steadModel, 'devices' => $devices, 'receipt' => $ReceiptType]);
                    } elseif ($ReceiptType->depends == 2) {
                        $devices = MeteringDevice::where('enable', 1)->where('type_id', $ReceiptType->id)->get();
                        foreach ($devices as $device) {
                            $device->rateNow();
                            $device->getLastIndication($steadModel->id);
                        }
                        return view('receipt/device1', ['stead' => $steadModel, 'devices' => $devices, 'receipt' => $ReceiptType]);
                    }
                }
            }
        }
        $ReceiptType = ReceiptTypeModels::all();
        return view('receipt/index', ['stead' => $steadModel, 'receipts' => $ReceiptType]);
    }

//    public function ticket($id, Request $request)
//    {
//        $stead = $request->stead;
//        $steadModel = Stead::where(['number' => $stead])->first();
//        $steadModel->setSession($request);
//        if ($request->save == "on") {
//            $steadModel->saveData($request);
//        }
//        $ReceiptTypeModels = ReceiptTypeModels::findOrFail((int)$id);
//        $ReceiptTypeModels->saveInstrumentReadings($steadModel, $request);
//        $cash = $ReceiptTypeModels->getCash($steadModel->id);
//        return view('receipt/ticket', ['stead' => $steadModel, 'receipt' => 1, 'devices' => $ReceiptTypeModels, 'cash' => $cash]);
//    }


}
