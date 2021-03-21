<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing;

use App\Http\Controllers\Admin\Bookkeeping\Billing\Balance\BalanceList\GetListController;
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
        $data = GetListController::getData($request);
        $total = count($data);
        $offset = ($request->page - 1) * $request->limit;
        $steads = $this->paginate($data, $request->page, $request->limit);
        return ['status' => true, 'total' => $total, 'offset' => $offset, 'data' => AdminBalansSteadResource::collection($steads)];

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
