<?php

namespace App\Http\Controllers\Admin\Receipt;

use App\Http\Controllers\Admin\Report\PdfController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Bookkeeping\AdminInvoiceResource;
use App\Models\Billing\BillingInvoice;
use App\Models\Receipt\ReceiptType;
use Illuminate\Http\Request;
use App\Models\Stead;
use App\Models\Receipt\InstrumentReadings;
use App\Models\Receipt\MeteringDevice;
use App\Models\Gardening;


class ReceiptListController extends Controller
{
    protected $query;
    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,access-admin-panel');
        $this->query = BillingInvoice::query();
    }


    public function index(Request $request)
    {
        $stead = $request->get('stead', false);
        $is_playment = $request->get('is_playment', false);
        $type = $request->get('type', false);
        $this->steadFilter($stead);
        $this->isPaymentFilter($is_playment);
        $this->typeFilter($type);
        $data = $this->query->paginate($request->limit);
        return AdminInvoiceResource::collection($data);

    }

    /**
     * фильтр по участку
     *
     * @param $stead
     */
    public function steadFilter($stead)
    {
        if ($stead) {
            $this->query->where('stead_id', $stead);
        }
    }

    /**
     * фильр оплаченно, не оплаченно
     * @param $is_playment
     */
    public function isPaymentFilter($is_playment)
    {
        if ($is_playment == 1) {
            $this->query->whereNotNull('payment_id');
        }
        if ($is_playment == 2) {
            $this->query->whereNull('payment_id');
        }
    }

    /**
     * фильтр по типу платежа
     *
     * @param $type
     */
    protected function typeFilter($type)
    {
        if ($type) {
            $this->query->where('type', $type);
        }
    }
//
//    public function getReceipt(Request $request)
//    {
//
//        if (isset($request->stead) and isset($request->receipt)) {
//            return PdfController::getReceipFoStead($request->stead, $request->receipt, true);
//        }
//        return 'error';
//    }
//
//
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
//
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
////        $discription = '';
////        $cash = 0;
////        foreach ($ReceiptType->MeteringDevice as $MeteringDevice) {
////            $cash += $MeteringDevice->getTicket($steadModel->id);
////            $discription .= $MeteringDevice->name.' '.$MeteringDevice->cash_description . ' ';
////        }
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
////        $rez['total'] = $steads->total();
////        if ($steads){
////            $steads= array_merge($steads->toArray(), ['status' => true]);
////            return $steads;
////        }
//        return json_encode(['status'=> true, 'data'=>$rez, 'total'=>$steads->total()]);
//    }

//    public function index($stread_id = false, Request $request)
//    {
//        $steadModel = false;
//        $receipt = (int)$request->receipt;
//        if ($request->isMethod('get')) {
//            $ReceiptType = ReceiptType::all();
//            return view('receipt/index', ['stead' => $steadModel, 'receipts' => $ReceiptType]);
//        }
//        if ($request->isMethod('post')) {
//
//            if (isset($request->stead) and isset($request->receipt)) {
//                $stead = $request->stead;
//                $ReceiptType = ReceiptType::findOrFail((int)$request->receipt);
//                $steadModel = Stead::where(['number' => $stead])->first();
//                if ($ReceiptType) {
//                    if ($ReceiptType->depends == 0) {
//
//                    } elseif ($ReceiptType->depends == 1) {
//                        $devices = MeteringDevice::where('enable', 1)->where('type_id', $ReceiptType->id)->get();
//                        foreach ($devices as $device) {
//                            $device->rateNow();
//                        }
//                        return view('receipt/device1', ['stead' => $steadModel, 'devices' => $devices, 'receipt' => $ReceiptType]);
//                    } elseif ($ReceiptType->depends == 2) {
//                        $devices = MeteringDevice::where('enable', 1)->where('type_id', $ReceiptType->id)->get();
//                        foreach ($devices as $device) {
//                            $device->rateNow();
//                            $device->getLastIndication($steadModel->id);
//                        }
//                        return view('receipt/device1', ['stead' => $steadModel, 'devices' => $devices, 'receipt' => $ReceiptType]);
//                    }
//                }
//            }
//        }
//        $ReceiptType = ReceiptType::all();
//        return view('receipt/index', ['stead' => $steadModel, 'receipts' => $ReceiptType]);
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
//        $ReceiptType = ReceiptType::findOrFail((int)$id);
//        $ReceiptType->saveInstrumentReadings($steadModel, $request);
//        $cash = $ReceiptType->getCash($steadModel->id);
//        return view('receipt/ticket', ['stead' => $steadModel, 'receipt' => 1, 'devices' => $ReceiptType, 'cash' => $cash]);
//    }
//


}
