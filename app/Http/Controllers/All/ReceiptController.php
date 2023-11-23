<?php

namespace App\Http\Controllers\All;

use App\Http\Controllers\Admin\Report\PdfController;
use App\Http\Controllers\Controller;
use App\Modules\Stead\Repositories\GetSteadByIdRepository;
use Illuminate\Http\Request;


class ReceiptController extends Controller
{


    public function getContributionsReceipt(Request $request)
    {
        if (isset($request->stead)) {
            $stead = (new GetSteadByIdRepository($request->stead))->run();
            $buffer = PdfController::getReceipFoStead($request->stead, 2, false);
            return response()->streamDownload(function () use ($buffer) {
                echo $buffer;
            }, 'Квитанция_' . $stead->number . '.pdf');
        }
        return 'error';
    }


//    public function getReceiptForListStead(Request $request)
//    {
//        $stead_min = isset($request->stead_min) || !empty($request->stead_min) ? $request->stead_min : false;
//        $stead_max = isset($request->stead_max) || !empty($request->stead_max) ? $request->stead_max : false;
//        $reestr = isset($request->reestr) || !empty($request->reestr) ? $request->reestr : false;
//        $fio = isset($request->fio) || !empty($request->fio) ? $request->fio : false;
//        $padding = isset($request->padding) || !empty($request->padding) ? $request->padding : true;
//        $query = Stead::query();
//        if ($stead_min){
//            $query->where('id', '>=', $stead_min );
//        }
//        if ($stead_max){
//            $query->where('id', '<=', $stead_max );
//        }
//        if ($padding) {
//            $steads =$query->paginate($request->limit);
//        } else {
//            set_time_limit(0);
//            $steads =$query->get();
//        }
//        return PdfController::getReceipFoSteadsList($steads, 2, $reestr, $fio);
//    }

//    public function getReestrForListStead(Request $request)
//    {
//        $stead_min = isset($request->stead_min) || !empty($request->stead_min) ? $request->stead_min : false;
//        $stead_max = isset($request->stead_max) || !empty($request->stead_max) ? $request->stead_max : false;
//        $query = Stead::query();
//        if ($stead_min){
//            $query->where('id', '>=', $stead_min );
//        }
//        if ($stead_max){
//            $query->where('id', '<=', $stead_max );
//        }
//        $steads =$query->paginate($request->limit);
//        $ReceiptType = ReceiptType::find(2);
//        $rez = [];
//        foreach ($steads as $stead) {
//            $temp = [];
//            $cash = 0;
//            foreach ($ReceiptType->MeteringDevice as $MeteringDevice) {
//                $temp[$MeteringDevice->name] = round($MeteringDevice->getTicket($stead->id), 2);
//                $cash += $MeteringDevice->getTicket($stead->id);
////                $discription .= $MeteringDevice->name.' '.$MeteringDevice->cash_description . ' ';
//            }
//              $temp['Итого'] = round($cash, 2);
//
//            $rez[] = ['number' => $stead->number, 'data'=>$temp];
//        }
//
//        return json_encode(['status'=> true, 'data'=>$rez, 'total'=>$steads->total()]);
//    }


}
