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

    public function stead($stead_id)
    {
        $this->invoice->stead_id = $stead_id;
        return $this;
    }

    public function field($field, $value)
    {
        if (in_array($field, ['description', 'comment'])) {
            $options = $this->invoice->options ?? [];
            $options[$field] = $value;
            $this->invoice->options = $options;
        } else {
            $this->invoice->$field = $value;
        }
        return $this;
    }


    public function run()
    {
        $description = 'Изменение счета';
        if (!$this->invoice->id) {
            $description = 'Создание счета';
        }
        if ($this->invoice->logAndSave($description)) {
            Cache::tags(['invoice'])->flush();
            return true;
        }
    }

}
