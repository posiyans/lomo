<?php

namespace App\Modules\Billing\Actions\Payment;

use App\Modules\Billing\Models\BillingPaymentModel;
use Illuminate\Support\Facades\Cache;

/**
 * Удалить платеж из счета
 *
 */
class DeletePaymentFromInvoiceAction
{

    private $payment;

    public function __construct(BillingPaymentModel $payment)
    {
        $this->payment = $payment;
    }

    /**
     * @throws \Exception
     */
    public function run(): BillingPaymentModel
    {
        $invoice = $this->payment->invoice;
        $this->payment->invoice_id = null;
        if ($this->payment->logAndSave('Удалние платежа из счета')) {
            $invoice->is_paid = false;
            $invoice->logAndSave('Удалние платежа из счета');
            Cache::tags(['payment', 'invoice'])->flush();
            return $this->payment;
        }
        return throw  new \Exception('Ошибка измения платежа');
    }

}
