<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Report\PdfController;
use App\Http\Controllers\Controller;
use App\Models\Gardening;
use App\Models\Stead;
use App\Modules\Receipt\Models\ReceiptTypeModels;
use Illuminate\Http\Request;


class ReceiptController extends Controller
{
    //


    public function getReceipt(Request $request)
    {
        if (isset($request->stead) and isset($request->receipt)) {
            return PdfController::getReceipFoStead($request->stead, $request->receipt, true);
        }
        return 'error';
    }


    public function getReceiptForListStead(Request $request)
    {
        $stead_min = isset($request->stead_min) || !empty($request->stead_min) ? $request->stead_min : false;
        $stead_max = isset($request->stead_max) || !empty($request->stead_max) ? $request->stead_max : false;
        $reestr = isset($request->reestr) || !empty($request->reestr) ? $request->reestr : false;
        $fio = isset($request->fio) || !empty($request->fio) ? $request->fio : false;
        $padding = isset($request->padding) || !empty($request->padding) ? $request->padding : true;
        $query = Stead::query();
        if ($stead_min) {
            $query->where('id', '>=', $stead_min);
        }
        if ($stead_max) {
            $query->where('id', '<=', $stead_max);
        }
        if ($padding) {
            $steads = $query->paginate($request->limit);
        } else {
            set_time_limit(0);
            $steads = $query->get();
        }
        return PdfController::getReceipFoSteadsList($steads, 2, $reestr, $fio);
    }

    public function getReestrForListStead(Request $request)
    {
        $stead_min = isset($request->stead_min) || !empty($request->stead_min) ? $request->stead_min : false;
        $stead_max = isset($request->stead_max) || !empty($request->stead_max) ? $request->stead_max : false;
        $query = Stead::query();
        if ($stead_min) {
            $query->where('id', '>=', $stead_min);
        }
        if ($stead_max) {
            $query->where('id', '<=', $stead_max);
        }
        $steads = $query->paginate($request->limit);
        $ReceiptType = ReceiptTypeModels::find(2);
//        $discription = '';
//        $cash = 0;
//        foreach ($ReceiptTypeModels->MeteringDevice as $MeteringDevice) {
//            $cash += $MeteringDevice->getTicket($steadModel->id);
//            $discription .= $MeteringDevice->name.' '.$MeteringDevice->cash_description . ' ';
//        }
        $rez = [];
        foreach ($steads as $stead) {
            $temp = [];
            $cash = 0;
            foreach ($ReceiptType->MeteringDevice as $MeteringDevice) {
                $temp[$MeteringDevice->name] = round($MeteringDevice->getTicket($stead->id), 2);
                $cash += $MeteringDevice->getTicket($stead->id);
//                $discription .= $MeteringDevice->name.' '.$MeteringDevice->cash_description . ' ';
            }
            $temp['Итого'] = round($cash, 2);

            $rez[] = ['number' => $stead->number, 'data' => $temp];
        }
//        $rez['total'] = $steads->total();
//        if ($steads){
//            $steads= array_merge($steads->toArray(), ['status' => true]);
//            return $steads;
//        }
        return json_encode(['status' => true, 'data' => $rez, 'total' => $steads->total()]);
    }

//    public function index($stread_id = false, Request $request)
//    {
//        $steadModel = false;
//        $receipt = (int)$request->receipt;
//        if ($request->isMethod('get')) {
//            $ReceiptTypeModels = ReceiptTypeModels::all();
//            return view('receipt/index', ['stead' => $steadModel, 'receipts' => $ReceiptTypeModels]);
//        }
//        if ($request->isMethod('post')) {
//
//            if (isset($request->stead) and isset($request->receipt)) {
//                $stead = $request->stead;
//                $ReceiptTypeModels = ReceiptTypeModels::findOrFail((int)$request->receipt);
//                $steadModel = Stead::where(['number' => $stead])->first();
//                if ($ReceiptTypeModels) {
//                    if ($ReceiptTypeModels->depends == 0) {
//
//                    } elseif ($ReceiptTypeModels->depends == 1) {
//                        $devices = MeteringDevice::where('enable', 1)->where('type_id', $ReceiptTypeModels->id)->get();
//                        foreach ($devices as $device) {
//                            $device->rateNow();
//                        }
//                        return view('receipt/device1', ['stead' => $steadModel, 'devices' => $devices, 'receipt' => $ReceiptTypeModels]);
//                    } elseif ($ReceiptTypeModels->depends == 2) {
//                        $devices = MeteringDevice::where('enable', 1)->where('type_id', $ReceiptTypeModels->id)->get();
//                        foreach ($devices as $device) {
//                            $device->rateNow();
//                            $device->getLastIndication($steadModel->id);
//                        }
//                        return view('receipt/device1', ['stead' => $steadModel, 'devices' => $devices, 'receipt' => $ReceiptTypeModels]);
//                    }
//                }
//            }
//        }
//        $ReceiptTypeModels = ReceiptTypeModels::all();
//        return view('receipt/index', ['stead' => $steadModel, 'receipts' => $ReceiptTypeModels]);
//    }
//
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
//


}
