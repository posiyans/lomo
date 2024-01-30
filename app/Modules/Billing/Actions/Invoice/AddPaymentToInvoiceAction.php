<?php

namespace App\Modules\Billing\Actions\Invoice;

use App\Modules\Billing\Models\BillingInvoiceModel;
use App\Modules\Billing\Models\BillingPaymentModel;

/**
 * Прикрепить платеж к счету
 *
 */
class AddPaymentToInvoiceAction
{

    private $payment;

    public function __construct(BillingInvoiceModel $invoice, BillingPaymentModel $payment)
    {
        $this->payment = $payment;
        $this->payment->invoice_id = $invoice->id;
    }

    public function run()
    {
        if ($this->payment->logAndSave('Прикрепление платежа к счету')) {
            return true;
        }
    }

}
