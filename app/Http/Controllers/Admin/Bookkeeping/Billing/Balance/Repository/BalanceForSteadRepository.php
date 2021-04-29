<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing\Balance\Repository;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Bookkeeping\AdminInvoiceResource;
use App\Http\Resources\Admin\Bookkeeping\AdminPaymentResource;
use App\Models\Billing\BillingInvoice;
use App\Models\Billing\BillingPayment;


class BalanceForSteadRepository extends Controller
{
    private $balans = [];

    /**
     * репозиторий для счетов и платежей по участку
     *
     * @param $stead_id
     * @param false $type фильтр по типу документа (
     * invoice - только счета,
     * invoice_no_paid - неоплаченные счета,
     * invoice_paid - оплаченные счета,
     * payment - платежи
     * payment_without_invoice' - платежи без счетов
     * @param false $receipt_type - тип платежей
     * @return array
     * @throws \Exception
     */
    public function getForStead($stead_id, $type = false, $receipt_type = false)
    {
        $this->getInvoice($stead_id, $type, $receipt_type);
        $this->getPayment($stead_id, $type, $receipt_type);
        krsort($this->balans);
        return array_values($this->balans);
    }


    private function getPayment($stead_id, $type, $receipt_type)
    {
        if ((!$type || $type == 'payment') || $type == 'payment_without_invoice') {
            $payments = BillingPayment::getPaymentForStead($stead_id);
            foreach ($payments as $payment) {
                $time = strtotime($payment->payment_date);
                while (isset($balans[$time])) {
                    $time++;
                }
                if ((!$receipt_type || $receipt_type == $payment->type) ||
                    ((!$receipt_type || $receipt_type == $payment->type) && $payment->invoice_id == null)
                ){
                    $this->balans[$time] = ['type' => 'payment', 'data' => new AdminPaymentResource($payment)];
                }
            }
        }
    }

    private function getInvoice($stead_id, $type, $receipt_type)
    {
        if (!$type || $type == 'invoice' || $type == 'invoice_no_paid' || $type == 'invoice_paid') {
            $invoices = BillingInvoice::getInvocesForStead($stead_id);
            foreach ($invoices as $invoice) {
                $time = strtotime($invoice->created_at);
                while (isset($balans[$time])) {
                    $time++;
                }
                if ((!$receipt_type || $receipt_type == $invoice->type) ||
                    ((!$receipt_type || $receipt_type == $invoice->type) && $invoice->paid == false) ||
                    ((!$receipt_type || $receipt_type == $invoice->type) && $invoice->paid == true)
                ){
                    $this->balans[$time] = ['type' => 'invoice', 'data' => new AdminInvoiceResource($invoice)];
                }
            }
        }
    }

}

