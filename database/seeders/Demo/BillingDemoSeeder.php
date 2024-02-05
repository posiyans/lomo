<?php

namespace Database\Seeders\Demo;

use App\Modules\Billing\Actions\Invoice\CreateInvoiceAction;
use App\Modules\Billing\Actions\InvoiceGroup\CreateInvoiceGroupAction;
use App\Modules\Billing\Actions\Payment\AddPaymentAction;
use App\Modules\Billing\Repositories\InvoiceRepository;
use App\Modules\MeteringDevice\Actions\AddInstrumentReadingAction;
use App\Modules\MeteringDevice\Actions\CheckDataInInstrumentReadingAction;
use App\Modules\MeteringDevice\Actions\CreateMeteringDeviceAction;
use App\Modules\MeteringDevice\Models\InstrumentReadingModel;
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
        $this->payment();
        $this->createMeteringDevice();
        self::$date = 0;
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

    private function createMeteringDevice()
    {
        echo __METHOD__ . "\n";
        $rate_types = RateTypeModel::where('rate_group_id', 2)->get();
        (new SteadRepository())->get()->each(function ($stead) use ($rate_types) {
            $devices = [
                'Ленэлектро ЛЕ 221.1.R2.DO',
                'Энергомера СЕ 102 R5.1 145-J 5-60',
                'ПЗИП ЦЭ 2726А А1-S-E4-R01',
                'Энергомера СЕ 102М R5 145-J 5-60А ',
                'Энергомера СЕ 102 R5.1 145-JAN'
            ];
            $options = [
                'serial_number' => '2014-00' . rand(1000, 9999),
                'device_brand' => $devices[rand(0, count($devices) - 1)],
                'installation_date' => null,
                'verification_date' => null,
            ];
            if (rand(0, 100) < 90) {
                foreach ($rate_types as $rate_type) {
                    $fill = [
                        'initial_data' => rand(100, 800),
                        'description' => '',
                        'active' => 1
                    ];
                    (new CreateMeteringDeviceAction($stead, $rate_type))
                        ->fill($fill)
                        ->options($options)
                        ->run();
                }
            }
        });
        $this->createReading();
    }


    private function createReading()
    {
        echo __METHOD__ . "\n";
        $count = $this->count_years * 6;
        (new SteadRepository())->get()->each(function ($stead) use ($count) {
            $devices = $stead->metering_devices;
            $date = strtotime('-' . $count . ' month');
            for ($i = 1; $i <= $count; $i++) {
                $date = strtotime('+1 month', $date);
                foreach ($devices as $device) {
                    if ($device->rate_type_id == 5) {
                        $value = $device->initial_data + rand(200, 500);
                    } else {
                        $value = $device->initial_data + rand(50, 200);
                    }
                    $device->initial_data = $value;
                    $reading = (new AddInstrumentReadingAction($device))
                        ->value($value)
                        ->date(date('Y-m-d', $date))
                        ->run();
                    (new CheckDataInInstrumentReadingAction())
                        ->after_reading($reading)
                        ->run();
                }
            }
        });
        $this->createReadingPaymentInvoice();
    }

    private function createReadingPaymentInvoice()
    {
        echo __METHOD__ . "\n";
        $steads = (new SteadRepository())->get();
        $d = 100;
        foreach ($steads as $stead) {
            $readings = InstrumentReadingModel::whereHas('metering_device', function ($query) use ($stead) {
                $query->where('stead_id', $stead->id);
            })
                ->orderBy('date', 'desc')
                ->offset(8)
                ->limit(100000)
                ->get();
            foreach ($readings->groupBy('date') as $groups) {
                $invoice = (new CreateInvoiceAction($stead))
                    ->rateGroup($groups[0]->metering_device->rate_type->rate_group_id)
                    ->title($groups[0]->metering_device->rate_type->rate_group->name . '.');
                foreach ($groups as $reading) {
                    $invoice->byInstrumentReading($reading);
                }
                $invoice = $invoice->run();

                $invoice->created_at = ($groups[0]->date . ' 10:10:10');
                $payment = null;
                $d += 150;
                if (rand(0, 100) < 85) {
                    $raw = [
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
                }
                $invoice->is_paid = $payment ? true : false;
                $invoice->save();
                foreach ($groups as $reading) {
                    $reading->payment_id = $payment->id ?? null;
                    $reading->invoice_id = $invoice->id;
                    $reading->save();
                }
            }
        }
    }


}
