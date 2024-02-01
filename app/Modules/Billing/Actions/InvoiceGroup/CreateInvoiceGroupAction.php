<?php

namespace App\Modules\Billing\Actions\InvoiceGroup;

use App\Modules\Billing\Models\BillingInvoiceGroupModel;
use Illuminate\Support\Facades\Auth;

/**
 * Прикрепить платеж к счету
 *
 */
class CreateInvoiceGroupAction
{

    private $invoice_group;

    public function __construct()
    {
        $this->invoice_group = new BillingInvoiceGroupModel();
        if (Auth::user()) {
            $this->invoice_group->user_id = Auth::user()->id;
        }
    }

    public function title($title)
    {
        if ($title) {
            $this->invoice_group->title = $title;
        }
        return $this;
    }

    public function rateGroup($rate_group)
    {
        if ($rate_group) {
            $this->invoice_group->rate_group_id = $rate_group;
        }
        return $this;
    }

    public function options($options)
    {
        if ($options) {
            $this->invoice_group->options = $options;
        }
        return $this;
    }


    public function run()
    {
        if ($this->invoice_group->logAndSave('Создание группы платежей')) {
            return $this->invoice_group;
        }
        throw new \Exception('Ошибка создания группы платежей');
    }

}
