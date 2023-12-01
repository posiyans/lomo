<?php

namespace App\Console\Commands\Migrate\Items;

use App\Modules\Stead\Models\SteadModel;
use Illuminate\Support\Facades\DB;

/**
 * конвертация меню
 *
 */
class SteadMigrate
{


    public static function run()
    {
        echo 'Конвертируем участки' . PHP_EOL;
        $categories = DB::connection('mysql_old')->table('steads')->get();
        foreach ($categories as $item) {
            $newItem = new SteadModel();
            $newItem->id = $item->id;
            $newItem->number = $item->number;
            $newItem->size = $item->size;
            $descriptions = json_decode($item->descriptions, true);
            unset($descriptions['fio']); //todo 
            $newItem->options = $descriptions;
            $newItem->created_at = $item->created_at;
            $newItem->save();
        }
    }

}
