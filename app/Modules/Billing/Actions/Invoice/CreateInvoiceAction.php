<?php

namespace App\Modules\Billing\Actions\Invoice;

use App\Modules\Billing\Models\BillingInvoiceGroupModel;
use App\Modules\Billing\Models\BillingInvoiceModel;
use App\Modules\MeteringDevice\Models\InstrumentReadingModel;
use App\Modules\MeteringDevice\Repositories\InstrumentReadingRepository;
use App\Modules\MeteringDevice\Repositories\PreviousReadingsModelRepository;
use App\Modules\Rate\Repositories\RateRepository;
use App\Modules\Stead\Models\SteadModel;
use App\Modules\Stead\Repositories\SteadRepository;
use Carbon\Carbon;

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
        $this->invoice->is_paid = false;
        $this->invoice->invoice_group_id = null;
        $this->invoice->user_id = \Auth::user()->id ?? null;
        $this->invoice->price = 0;
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

    public function price($price)
    {
        if ($price) {
            $this->invoice->price = $price;
        }
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
            $options['description'] .= $text . '@';
        } else {
            $options['description'] = $text . '@';
        }
        $this->invoice->options = $options;
    }


    public function run()
    {
        if ($this->invoice->logAndSave('Создание счета')) {
            return $this->invoice;
        }
        throw new \Exception('Ошибка создания счета');
    }

    /**
     * выставить счета на основании группы счетов
     *
     * @param BillingInvoiceGroupModel $invoice_group группа счетов
     * @param array|null $steads_id Участки (null значит всем)
     * @return array
     */
    public static function byInvoiceGroup(BillingInvoiceGroupModel $invoice_group, array|false|null $steads_id = false)
    {
        $steads = new SteadRepository();
        if ($steads_id) {
            $steads->findById($steads_id);
        }
        $steads = $steads->get();
        $invoices = [];
        foreach ($steads as $stead) {
            $invoice = (new self($stead))
                ->invoiceGroup($invoice_group);
            $rate_group = $invoice_group->rateGroup;
            if ($rate_group->depends == 1) {
                $invoice->title($invoice_group->title)
                    ->setPriceForRates($invoice_group->options['rate'])
                    ->setDescriptionForRates($invoice_group->options['rate'])
                    ->setTotal();
                $invoices[] = $invoice->run();
            }
            if ($rate_group->depends == 2) {
                $rate_type = collect($invoice_group->options['rate'])->map(function ($value) {
                    return $value['id'];
                });
                $invoice_date = $invoice_group->options['invoice_date'];
                $date_start = (new Carbon($invoice_date))->startofMonth()->toDateString();
                $date_end = (new Carbon($invoice_date))->addMonth()->startofMonth()->toDateString();
                $readings = (new InstrumentReadingRepository())
                    ->forRateType($rate_type->toArray())
                    ->between_date($date_start, $date_end)
                    ->for_stead($stead->id)
                    ->noInvoice()
                    ->get();
                if ($readings->count() > 0) {
                    $text_date = (new Carbon($invoice_date))->rawFormat('m-Y');
                    $invoice->title($readings[0]->metering_device->rate_type->rate_group->name . ' ' . $text_date);
                    foreach ($readings as $reading) {
                        $invoice->byInstrumentReading($reading);
                    }
                    $invoice->setTotal();
                    $invoice = $invoice->run();
                    foreach ($readings as $reading) {
                        $reading->invoice_id = $invoice->id;
                        $reading->save();
                    }
                    $invoices[] = $invoice;
                }
            }
        }
        return $invoices;
    }


    private function getPriceForRate($rate)
    {
        return $this->stead_size * $rate['rate']['ratio_a'] + $rate['rate']['ratio_b'];
    }

    private function setPriceForRates($rates)
    {
        $sum = 0;
        foreach ($rates as $rate) {
            $sum += $this->getPriceForRate($rate);
        }
        $this->invoice->price = $sum;
        return $this;
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

    private function setDescriptionForRates($rates): CreateInvoiceAction
    {
        $text = '';
        foreach ($rates as $rate) {
            $text .= $this->getDescriptionForRate($rate);
        }
        $options = $this->invoice->options;
        $options['description'] = $text;
        $this->invoice->options = $options;
        return $this;
    }

    private function setTotal()
    {
        $options = $this->invoice->options;
        $text = $options['description'] ?? '';
        $text .= 'Итого: ' . $this->invoice->price . ' руб;';
        $options['description'] = $text;
        $this->invoice->options = $options;
        return $this;
    }


}
