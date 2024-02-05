<?php

namespace App\Modules\Billing\Actions\Invoice;

use App\Modules\Billing\Models\BillingInvoiceGroupModel;
use App\Modules\Billing\Models\BillingInvoiceModel;
use App\Modules\MeteringDevice\Models\InstrumentReadingModel;
use App\Modules\MeteringDevice\Repositories\PreviousReadingsModelRepository;
use App\Modules\Rate\Repositories\RateRepository;
use App\Modules\Stead\Models\SteadModel;

/**
 * Прикрепить платеж к счету
 *
 */
class CreateInvoiceAction
{

    private $invoice;
    private $stead_size;
    private $reading;

    public function __construct(SteadModel $stead)
    {
        $this->invoice = new BillingInvoiceModel();
        $this->invoice->stead_id = $stead->id;
        $this->stead_size = $stead->size / 100;
        $this->invoice->is_paid = false;
        $this->invoice->invoice_group_id = null;
        $this->invoice->user_id = \Auth::user()->id ?? null;
        $this->invoice->price = 0;
        $this->reading = [];
    }


    public function rateGroup($rate_group_id)
    {
        if ($rate_group_id) {
            $this->invoice->rate_group_id = $rate_group_id;
        }
        return $this;
    }

    public function title($title)
    {
        if ($title) {
            $this->invoice->title = $title;
        }
        return $this;
    }

    public function byInvoiceGroup(BillingInvoiceGroupModel $invoice_group)
    {
        $this->invoiceGroup($invoice_group);
        $this->invoice->title = $invoice_group->title;
        $this->invoice->price = $this->getPriceForRates($invoice_group->options);
        $this->invoice->options = ['description' => $this->getDescriptionForRates($invoice_group->options)];
        return $this;
    }

    public function invoiceGroup(BillingInvoiceGroupModel $invoice_group)
    {
        $this->invoice->rate_group_id = $invoice_group->rate_group_id;
        $this->invoice->invoice_group_id = $invoice_group->id;
        return $this;
    }


    public function byInstrumentReading(InstrumentReadingModel $reading)
    {
        $this->reading[] = $reading;
        $this->invoice->title .= ', ' . $reading->metering_device->rate_type->name;
        $rate_type = $reading->metering_device->rate_type;
        $rate_group = $rate_type->rate_group;
        $rate = RateRepository::for_instrument_reading($reading);
        $old_reading = new PreviousReadingsModelRepository($reading);
        $old_value = $old_reading->value();
        $delta = $old_reading->delta();

        $this->invoice->price += $delta * $rate->ratio_a;
        $options = $this->invoice->options;
        $text = $reading->metering_device->rate_type->name . ': ';
        $text .= $reading->value . ' ' . $rate_group->options['unit_name'] . ' - ' . $old_value . $rate_group->options['unit_name'] . ' = ' . $delta . ' ' . $rate_group->options['unit_name'];
        $text .= ' * ' . $rate->description . ' = ' . $delta * $rate->ratio_a . ' руб.';
        if (isset($options['description'])) {
            $options['description'] .= '@' . $text;
        } else {
            $options['description'] = $text;
        }
        $this->invoice->options = $options;
    }


    public function run()
    {
        if ($this->invoice->logAndSave('Создание счета')) {
            foreach ($this->reading as $item) {
                $item->invoice_id = $this->invoice->id;
                $item->save();
            }
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
        $text .= 'Итого: ' . $this->getPriceForRates($rates) . ' руб;';
        return $text;
    }


}
