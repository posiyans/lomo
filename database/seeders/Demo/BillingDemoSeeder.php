<?php

namespace Database\Seeders\Demo;

use App\Modules\Billing\Actions\Invoice\CreateInvoiceAction;
use App\Modules\Billing\Actions\InvoiceGroup\CreateInvoiceGroupAction;
use App\Modules\Billing\Actions\Payment\AddPaymentAction;
use App\Modules\Billing\Repositories\InvoiceRepository;
use App\Modules\Rate\Actions\CreateRateTypeAction;
use App\Modules\Rate\Models\RateModel;
use App\Modules\Rate\Models\RateTypeModel;
use App\Modules\Rate\Repositories\RateGroupRepository;
use App\Modules\Stead\Repositories\SteadRepository;
use Illuminate\Database\Seeder;

class BillingDemoSeeder extends Seeder
{

    private $count_years = 10;
    private static $date;

    public function run()
    {
        echo get_class($this) . "\n";
        $this->rate();
        $this->invoice();
        self::$date = 0;
        $this->payment();
    }

    private function rate()
    {
        $data = [
            [
                'label' => 'Взносы',
                'depends' => 1,
                'payment_period' => 12,
                'options' => [
                    'tag' => ["взнос", "членск", "целев"],
                ],
                'types' => [
                    [
                        'name' => 'Членский взнос',
                        'description' => 'Оплата членского взноса',
                        'val_start' => 598,
                        'val_delta' => 100,
                        'field' => 1,
                        'desc' => ' с сотки'
                    ],
                    [
                        'name' => 'Целевой взнос',
                        'description' => 'Оплата целевого взноса',
                        'val_start' => 325,
                        'val_delta' => 100,
                        'field' => 1,
                        'desc' => ' с сотки'
                    ],
                    [
                        'name' => 'Земельный налог',
                        'description' => 'Оплата земельного налога',
                        'val_start' => 25,
                        'val_delta' => 1,
                        'field' => 1,
                        'desc' => ' с сотки'
                    ],
                    [
                        'name' => 'Вывоз мусора',
                        'description' => 'Оплата вывоза мусора',
                        'val_start' => 300,
                        'val_delta' => 50,
                        'field' => 2,
                        'desc' => ' с участка'
                    ],
                ]
            ],
            [
                'label' => 'Электричество',
                'depends' => 2,
                'payment_period' => 1,
                'options' => [
                    'unit_name' => 'кВт/ч',
                    'tag' => ["энер", "свет", "эл", "эн.", "ээ", "квт", "счетчик", "показан"],
                ],
                'types' => [
                    [
                        'name' => 'Электр. День',
                        'description' => 'Оплата элентроэнергии по дневному тарифу',
                        'val_start' => 3,
                        'val_delta' => 0.53,
                        'field' => '1',
                        'desc' => ' руб*кВт/ч'
                    ],
                    [
                        'name' => 'Электр. Ночь',
                        'description' => 'Оплата элентроэнергии по ночному тарифу',
                        'val_start' => 2,
                        'val_delta' => 0.52,
                        'field' => '1',
                        'desc' => ' руб*кВт/ч'
                    ]
                ]
            ],
        ];
        foreach ($data as $item) {
            $group = (new CreateRateTypeAction($item['label']))->run();
            $group->depends = $item['depends'] ?? 1;
            $group->payment_period = $item['payment_period'] ?? 12;
            $group->options = $item['options'] ?? [];
            $group->save();
            foreach ($item['types'] as $type) {
                $rate_type = new RateTypeModel();
                $rate_type->rate_group_id = $group->id;
                $rate_type->name = $type['name'] ?? '';
                $rate_type->description = $type['description'] ?? '';
                $rate_type->save();
                $val = $type['val_start'] ?? 2;
                $delta = $type['val_delta'] ?? 0.5;
                for ($i = 1; $i <= $this->count_years; $i++) {
                    $newItem = new RateModel();
                    $newItem->ratio_a = $type['field'] == 1 ? $val : 0;
                    $newItem->ratio_b = $type['field'] == 2 ? $val : 0;
                    $newItem->description = $val . $type['desc'];
                    $newItem->rate_type_id = $rate_type->id;
                    $newItem->date_start = date('Y') - $this->count_years + $i . '-01-01';
                    $newItem->save();
                    $val += round($delta * (rand(0, 10) / 10), 2);
                }
            }
        }
    }

    private function invoice()
    {
        echo __METHOD__ . "\n";
        $rate_group = (new RateGroupRepository())->byId(1);
        $rate_types = $rate_group->rateType;
        for ($i = 1; $i <= $this->count_years; $i++) {
            $title = 'Взносы ' . date('Y') - $this->count_years + $i . ', Членский взнос, Целевой взнос, Земельный налог, Вывоз мусора';
            $rate = [];
            $curDate = date('Y') - $this->count_years + $i . '-06-01';
            foreach ($rate_types as $rate_type) {
                $tmp = $rate_type->toArray();
                $r = RateTypeModel::find($rate_type->id);
                $r->setCurrentDate($curDate);
                $tmp['rate'] = $r->currentRate;
                $rate[] = $tmp;
            }
            $invoiceGroup = (new CreateInvoiceGroupAction())
                ->title($title)
                ->rateGroup($rate_group->id)
                ->options($rate)
                ->run();
            $invoiceGroup->created_at = date('Y') - $this->count_years + $i . '-06-01 10.10.10';
            $invoiceGroup->save();
            (new SteadRepository())->get()->each(function ($stead) use ($invoiceGroup, $i) {
                $invoice = (new CreateInvoiceAction($stead))->byInvoiceGroup($invoiceGroup)->run();
                $invoice->created_at = (date('Y') - $this->count_years + $i . '-06-01 10:10:10');
                $invoice->save();
            });
        }
    }


    public function payment()
    {
        echo __METHOD__ . "\n";

        (new InvoiceRepository())->get()->each(function ($invoice) {
            $d = self::$date;
            self::$date = self::$date + 100;
            if (rand(0, 100) < 95) {
                $raw = [
//                    date('Y-m-d h:i:s', $d),
                    date('Y-m-d h:i:s', strtotime($invoice->created_at) + $d),
                    $invoice->price,
                    $invoice->stead->number,
                    $invoice->stead->owners[0]->smallName(),
                    $invoice->title
                ];
                $payment = (new AddPaymentAction())->parseRawData($raw)->run();
                $payment->rate_group_id = $invoice->rate_group_id;
                $payment->invoice_id = $invoice->id;
                $payment->error = false;
                $payment->created_at = date('Y-m-d h:i:s', strtotime($invoice->created_at) + $d);
                $payment->save();
                $invoice->is_paid = true;
                $invoice->save();
            }
        });
    }
}
