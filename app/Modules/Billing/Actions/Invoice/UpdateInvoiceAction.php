<?php

namespace App\Modules\Billing\Actions\Invoice;

use App\Modules\Billing\Models\BillingInvoiceModel;
use Illuminate\Support\Facades\Cache;

/**
 * Обновить счет
 *
 */
class UpdateInvoiceAction
{

    private $invoice;

    public function __construct(BillingInvoiceModel $invoice)
    {
        $this->invoice = $invoice;
    }


    public function field($field, $value)
    {
        if (in_array($field, ['description', 'comment'])) {
            $desc = $this->invoice->description;
            $desc[$field] = $value;
            $this->invoice->description = $desc;
        } else {
            $this->invoice->$field = $value;
        }
        return $this;
    }


    public function run()
    {
        if ($this->invoice->logAndSave('Изменение счета')) {
            Cache::tags(['invoice'])->flush();
            return true;
        }
    }

}
