<?php

namespace App\Modules\Billing\Actions\Invoice;

use App\Modules\Billing\Models\BillingInvoiceGroupModel;
use App\Modules\Billing\Models\BillingInvoiceModel;
use App\Modules\Stead\Models\SteadModel;

/**
 * Прикрепить платеж к счету
 *
 */
class CreateInvoiceAction
{

    private $invoice;
    private $stead_size;

    public function __construct(SteadModel $stead)
    {
        $this->invoice = new BillingInvoiceModel();
        $this->invoice->stead_id = $stead->id;
        $this->stead_size = $stead->size / 100;
    }

    public function byInvoiceGroup(BillingInvoiceGroupModel $invoice_group)
    {
        $this->invoice->title = $invoice_group->title;
        $this->invoice->rate_group_id = $invoice_group->rate_group_id;
        $this->invoice->invoice_group_id = $invoice_group->id;
        $this->invoice->is_paid = false;
        $this->invoice->user_id = \Auth::user()->id ?? null;
        $this->invoice->price = $this->getPriceForRates($invoice_group->options);
        $this->invoice->options = ['description' => $this->getDescriptionForRates($invoice_group->options)];
        return $this;
    }


    public function run()
    {
        if ($this->invoice->logAndSave('Создание счета')) {
            return $this->invoice;
        }
        throw new \Exception('Ошибка создания счета');
    }


    private function getPriceForRate($rate)
    {
        return $this->stead_size * $rate['rate']['ratio_a'] + $rate['rate']['ratio_b'];
    }

    private function getPriceForRates($rates)
    {
        $sum = 0;
        foreach ($rates as $rate) {
            $sum += $this->getPriceForRate($rate);
        }
        return $sum;
    }

    private function getDescriptionForRate($rate)
    {
        $text = $rate['name'] . ': ';
        $ratio_a = $rate['rate']['ratio_a'] ?? 0;
        $ratio_b = $rate['rate']['ratio_b'] ?? 0;
        if ($ratio_a > 0 || $ratio_b > 0) {
            if ($ratio_a > 0) {
                $text .= $this->stead_size . ' * ' . $ratio_a . ' руб с сотки';
                $text .= ' = ' . $ratio_a * $this->stead_size . ' руб';
            }
            if ($ratio_b > 0) {
                $text .= $ratio_b . ' руб с участка';
            }
            $text .= ';@';
        }
        return $text;
    }

    private function getDescriptionForRates($rates): string
    {
        $text = '';
        foreach ($rates as $rate) {
            $text .= $this->getDescriptionForRate($rate);
        }
        return $text;
    }


}
