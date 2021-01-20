<?php

namespace App\Http\Controllers;

use App\Models\Receipt\ReceiptType;
use Illuminate\Http\Request;
use App\Models\Stead;
use App\Models\Receipt\InstrumentReadings;
use App\Models\Receipt\MeteringDevice;
use App\Models\Gardening;


class ReceiptController extends Controller
{
    //


    public function index($stread_id = false, Request $request)
    {
        $steadModel = false;
        $receipt = (int)$request->receipt;
        if ($request->isMethod('get')) {
            $ReceiptType = ReceiptType::all();
            return view('receipt/index', ['stead' => $steadModel, 'receipts' => $ReceiptType]);
        }
        if ($request->isMethod('post')) {

            if (isset($request->stead) and isset($request->receipt)) {
                $stead = $request->stead;
                $ReceiptType = ReceiptType::findOrFail((int)$request->receipt);
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
        $ReceiptType = ReceiptType::all();
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
//        $ReceiptType = ReceiptType::findOrFail((int)$id);
//        $ReceiptType->saveInstrumentReadings($steadModel, $request);
//        $cash = $ReceiptType->getCash($steadModel->id);
//        return view('receipt/ticket', ['stead' => $steadModel, 'receipt' => 1, 'devices' => $ReceiptType, 'cash' => $cash]);
//    }



}
