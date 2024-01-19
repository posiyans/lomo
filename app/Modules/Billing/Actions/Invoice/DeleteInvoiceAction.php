<?php

namespace App\Modules\Billing\Actions\Invoice;

use App\Modules\Billing\Models\BillingInvoiceModel;
use Illuminate\Support\Facades\Cache;

/**
 * Удалить счет
 *
 */
class DeleteInvoiceAction
{

    private $invoice;

    public function __construct(BillingInvoiceModel $invoice)
    {
        $this->invoice = $invoice;
    }


    public function run()
    {
        $this->unlink_payments();
        if ($this->invoice->logAndDelete('Удаление счета')) {
            Cache::tags(['invoice'])->flush();
            return true;
        }
    }


    private function unlink_payments()
    {
        $payments = $this->invoice->payments;
        foreach ($payments as $payment) {
            $payment->invoice_id = null;
            $payment->logAndSave('Удаление родителького счета');
        }
    }


}
