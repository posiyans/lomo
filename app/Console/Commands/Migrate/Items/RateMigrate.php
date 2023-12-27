<?php

namespace App\Console\Commands\Migrate\Items;

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
    }
}

class RateGroupModel extends RateGroupModelOriginal
{
    protected $casts = [];
}
