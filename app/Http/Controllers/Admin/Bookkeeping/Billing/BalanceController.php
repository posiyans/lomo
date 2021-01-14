<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing;

use App\Http\Resources\Admin\Bookkeeping\AdminBalansSteadResource;
use App\Http\Resources\Admin\Bookkeeping\AdminPaymentResource;
use App\Models\Billing\BillingInvoice;
use App\Models\Billing\BillingPayment;
use App\Models\Billing\BillingReestr;
use App\Models\Stead;
use App\Permission;
use App\Role;
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        if ($request->category || $request->payment) {
            $steads = Stead::all();
            $cat = [];
            if ($request->category) {
                foreach ($steads as $stead) {
                    if ($request->category == 1 && $stead->getBalans() >= 0) {
                        $cat[] = $stead;
                    } else if ($request->category == 2 && $stead->getBalans() < 0) {
                        $cat[] = $stead;
                    } else if ($request->category == 3 && $stead->getBalans(2) < 0) {
                        $cat[] = $stead;
                    } else if ($request->category == 4 && $stead->getBalans(1) < 0) {
                        $cat[] = $stead;
                    }
                }
            } else {
                $cat = $steads;
            }
            $data = [];
            if ($request->payment && $request->month) {
                $now = strtotime("-".(int)$request->month." month");
                foreach ($cat as $item) {
                    if ($item->lastPayment) {
                        $d = strtotime($item->lastPayment->payment_date);
//                        return json_encode([$d, $now]);
                        if ($request->payment == 1 && $d < $now) {
                            $data[] = $item;
                        } else if ($request->payment == 2 && $d > $now) {
                            $data[] = $item;
                        }
                    } else {
                        if ($request->payment == 1) {
                          $data[] = $item;
                        }
                    }
                }
            } else {
                $data = $cat;
            }
            $total = count($data);
            if ($request->limit && $request->page) {
                $r = [];
                $i = 1;
                foreach ($data as $item) {
                    if ($i > $request->limit * ($request->page-1) && $i <= $request->limit * ($request->page)) {
                        $r[] = [
                            'id'=>$item->id,
                            'number' => $item->number,
                            'size' => $item->size,
                            'balans' => round($item->getBalans(), 2),
                            'balans_1' => round($item->getBalans(1), 2),
                            'balans_2' => round($item->getBalans(2), 2),
                            'last_payment' => $item->lastPayment,
                        ];
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
        $rezult['stead_info'] = new AdminBalansSteadResource($stead);
        $invoices = BillingInvoice::getInvocesForStead($stead->id);
        $payments = BillingPayment::getPaymentForStead($stead->id);
        $balans = [];
        foreach ($invoices as $invoice) {
            $time = strtotime($invoice->created_at);
            while (isset($balans[$time])) {
                $time++;
            }
            $balans[$time] = ['type' => 'invoice', 'data'=>$invoice];
        }
        foreach ($payments as $payment) {
            $time = strtotime($payment->payment_date);
            while (isset($balans[$time])) {
                $time++;
            }
            $balans[$time] = ['type' => 'payment', 'data'=>new AdminPaymentResource($payment)];
        }
        ksort($balans);
        $rezult['invoices'] = array_values($balans);

        return ['status'=>true, 'data'=>$rezult];
//        $query = BillingReestr::query();
//        $article = $query->orderBy('created_at', 'desc')->paginate($request->limit);
//        return ['status'=>true, 'data'=>$article, 'total'=>$article->total()];
    }

}
