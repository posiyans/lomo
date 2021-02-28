<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing;

use App\Http\Resources\Admin\Bookkeeping\AdminBalansSteadResource;
use App\Http\Resources\Admin\Bookkeeping\AdminInvoiceResource;
use App\Http\Resources\Admin\Bookkeeping\AdminPaymentResource;
use App\Models\Billing\BillingInvoice;
use App\Models\Billing\BillingPayment;
use App\Models\Billing\BillingReestr;
use App\Models\Receipt\ReceiptType;
use App\Models\Stead;
use App\Models\Laratrust\Permission;
use App\Models\Laratrust\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class BalanceController extends Controller
{


    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,access-admin-panel');
    }

    /**
     * список участков  балансом
     *
     * $request->category  с долгами, или без
     * $request->payment когда был последний платеж
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        if ($request->category || $request->payment) {
            $steads = Stead::all();
            $cat = [];
            if ($request->category) {
                foreach ($steads as $stead) {
                    $balans = $stead->getBalans($request->get('receipt_type', false));
                    if ($request->category == 1 && $balans >= 0) {
                        $cat[] = $stead;
                    } else if ($request->category == 2 && $balans < 0) {
                        $cat[] = $stead;
                    }
                }
            } else {
                $cat = $steads;
            }
            $data = [];
//            if ($request->payment && $request->month) {
//                $now = strtotime("-".(int)$request->month." month");
//                foreach ($cat as $item) {
//                    if ($item->lastPayment) {
//                        $d = strtotime($item->lastPayment->payment_date);
////                        return json_encode([$d, $now]);
//                        if ($request->payment == 1 && $d < $now) {
//                            $data[] = $item;
//                        } else if ($request->payment == 2 && $d > $now) {
//                            $data[] = $item;
//                        }
//                    } else {
//                        if ($request->payment == 1) {
//                          $data[] = $item;
//                        }
//                    }
//                }
//            } else {
                $data = $cat;
//            }
            $total = count($data);
            if ($request->limit && $request->page) {
                $r = [];
                $i = 1;
                foreach ($data as $item) {
                    if ($i > $request->limit * ($request->page-1) && $i <= $request->limit * ($request->page)) {
                        $temp = [
                            'id'=>$item->id,
                            'number' => $item->number,
                            'size' => $item->size,
                            'balans_all' => round($item->getBalans(), 2),
//                            'balans_1' => round($item->getBalans(1), 2),
//                            'balans_2' => round($item->getBalans(2), 2),
                            'last_payment' => $item->lastPayment,
                        ];
                        $types = ReceiptType::getReceiptTypeIds();
                        $temp_balans = [];
                        foreach ($types  as $type) {
                            $temp_balans['d'.$type] = round($item->getBalans($type), 2);
                        }
                        $temp['balans'] =  $temp_balans;
                        $r[] = $temp;
                    }

                    $i++;
                }
            } else {
                $r = $data;
            }
//            return AdminBalansSteadResource::collection($data);
            return json_encode(['status'=>true, 'data'=>$r, 'meta' => ['total'=>$total]]);
        } else {
            $query = Stead::query();
            if ($request->find) {
                $query->where('number', 'like', "%$request->find%");
            }
            $steads = $query->paginate($request->limit);
            return AdminBalansSteadResource::collection($steads);
        }
    }

    public function info(Request $request)
    {
        $rezult = [];
        $stead = Stead::find($request->stead_id);
        $balans = [];
        $type = $request->get('type', false);
        $receipt_type = $request-> get('receiptType', false);
        if (!$type || $type == 'invoice') {
            $invoices = BillingInvoice::getInvocesForStead($stead->id);
            foreach ($invoices as $invoice) {
                $time = strtotime($invoice->created_at);
                while (isset($balans[$time])) {
                    $time++;
                }
                if (!$receipt_type || $receipt_type == $invoice->type) {
                    $balans[$time] = ['type' => 'invoice', 'data' => new AdminInvoiceResource($invoice)];
                }
            }
        }
        if ($type == 'invoice_no_paid') {
            $invoices = BillingInvoice::getInvocesForStead($stead->id);
            foreach ($invoices as $invoice) {
                $time = strtotime($invoice->created_at);
                while (isset($balans[$time])) {
                    $time++;
                }
                if ((!$receipt_type || $receipt_type == $invoice->type) && $invoice->paid == false) {

                    $balans[$time] = ['type' => 'invoice', 'data' => new AdminInvoiceResource($invoice)];
                }
            }
        }
        if ($type == 'invoice_paid') {
            $invoices = BillingInvoice::getInvocesForStead($stead->id);
            foreach ($invoices as $invoice) {
                $time = strtotime($invoice->created_at);
                while (isset($balans[$time])) {
                    $time++;
                }
                if ((!$receipt_type || $receipt_type == $invoice->type) && $invoice->paid == true) {
                    $balans[$time] = ['type' => 'invoice', 'data' => new AdminInvoiceResource($invoice)];
                }
            }
        }
        if (!$type || $type == 'payment') {
            $payments = BillingPayment::getPaymentForStead($stead->id);
            foreach ($payments as $payment) {
                $time = strtotime($payment->payment_date);
                while (isset($balans[$time])) {
                    $time++;
                }
                if (!$receipt_type || $receipt_type == $payment->type) {
                    $balans[$time] = ['type' => 'payment', 'data' => new AdminPaymentResource($payment)];
                }
            }
        }
        if ($type == 'payment_without_invoice') {
            $payments = BillingPayment::getPaymentForStead($stead->id);
            foreach ($payments as $payment) {
                $time = strtotime($payment->payment_date);
                while (isset($balans[$time])) {
                    $time++;
                }
                if ((!$receipt_type || $receipt_type == $payment->type) && $payment->invoice_id == null) {
                    $balans[$time] = ['type' => 'payment', 'data' => new AdminPaymentResource($payment)];
                }
            }
        }
        krsort($balans);
        $rezult['total'] = count($balans);
        $rezult['data'] = array_values($this->paginate($balans, $request->page, $request->limit));
        $rezult['status'] = true;

        return $rezult;
//        $query = BillingReestr::query();
//        $article = $query->orderBy('created_at', 'desc')->paginate($request->limit);
//        return ['status'=>true, 'data'=>$article, 'total'=>$article->total()];
    }


    public function allBalance(Request $request)
    {
       $stead = Stead::find($request->id);
        $types = ReceiptType::all();
        $result = ['balans_all' => round($stead->getBalans(), 2)];
        $temp_balans = [];
        foreach ($types as $type) {
            $temp_balans[] = ['name' => $type->name, 'price' => round($stead->getBalans($type->id), 2)];
        }
        $result['balans'] = $temp_balans;
       return ['status' => true, 'data' => $result];
    }
}
