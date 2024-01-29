<?php

namespace App\Console\Commands\Migrate\Items;

use App\Models\MyModel;
use App\Modules\Rate\Models\RateGroupModel as RateGroupModelOriginal;
use App\Modules\Rate\Models\RateModel;
use App\Modules\Rate\Models\RateTypeModel;
use Illuminate\Support\Facades\DB;

/**
 * конвертация тарифов
 *
 */
class RateMigrate
{
    private static $fields = [
        'id' => 'id',
        'name' => 'name',
        'auto_invoice' => 'auto_invoice',
        'options' => 'options',
        'depends' => 'depends',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
        'payment_period' => 'payment_period',
    ];


    public static function run()
    {
        echo 'Конвертируем тарифы' . PHP_EOL;
        $rate_types = DB::connection('mysql_old')->table('receipt_types')->get();
        foreach ($rate_types as $item) {
            $newItem = new RateGroupModel();
            foreach (self::$fields as $key => $value) {
                $newItem->$key = $item->$value;
            }

            $newItem->save();
        }
        $rates = DB::connection('mysql_old')->table('metering_devices')->get();
        $fields = ['id', 'name', 'enable', 'description', 'created_at', 'updated_at'];
        foreach ($rates as $item) {
            $newItem = new RateTypeModel();
            foreach ($fields as $value) {
                $newItem->$value = $item->$value;
            }
            $newItem->rate_group_id = $item->type_id;
            $newItem->save();
        }

        $rates = DB::connection('mysql_old')->table('rates')->get();
        $fields = ['id', 'created_at', 'updated_at'];
        foreach ($rates as $item) {
            $newItem = new RateModel();
            foreach ($fields as $value) {
                $newItem->$value = $item->$value;
            }
            $newItem->ratio_a = str_replace(',', '.', $item->ratio_a);
            $newItem->ratio_b = str_replace(',', '.', $item->ratio_b);
            $newItem->description = str_replace(',', '.', $item->description);

            $newItem->rate_type_id = $item->device_id;
            $newItem->date_start = $item->created_at;
            $newItem->save();
        }

        $devices = DB::connection('mysql_old')->table('device_register')->get();
        $fields = ['id', 'stead_id', 'initial_data', 'active', 'created_at', 'updated_at'];
        // 'serial_number', 'device_brand', 'installation_date', 'verification_date',
        foreach ($devices as $item) {
            $newItem = new MeteringDeviceModel();
            foreach ($fields as $value) {
                $newItem->$value = $item->$value;
            }
            $newItem->rate_type_id = $item->type_id;
            $options = [
                'serial_number' => $item->serial_number,
                'device_brand' => $item->device_brand,
                'installation_date' => $item->installation_date,
                'verification_date' => $item->verification_date,
            ];
            $newItem->description = $item->descriptions;
            $newItem->options = $options;
            $newItem->save();
        }

        $readings = DB::connection('mysql_old')->table('instrument_readings')->get();
        $fields = ['id', 'value', 'invoice_id', 'payment_id', 'created_at', 'updated_at'];
        foreach ($readings as $item) {
            $newItem = new InstrumentReadingModel();
            foreach ($fields as $value) {
                $newItem->$value = $item->$value;
            }
            $newItem->metering_device_id = $item->device_id;
            $newItem->date = $item->created_at;
            $newItem->save();
        }
    }
}

class RateGroupModel extends RateGroupModelOriginal
{
    protected $casts = [];
    public $timestamps = false;
}

class MeteringDeviceModel extends MyModel
{

    protected $casts = [
        'enable' => 'boolean',
        'options' => 'array',
    ];
    public $timestamps = false;
}

class InstrumentReadingModel extends MyModel
{
    public $timestamps = false;
    protected $casts = [
        'value' => 'decimal:2',
    ];
}