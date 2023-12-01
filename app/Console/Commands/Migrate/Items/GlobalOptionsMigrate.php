<?php

namespace App\Console\Commands\Migrate\Items;

use App\Modules\Setting\Models\GlobalOptionModel as GlobalOptionModelOriginal;
use Illuminate\Support\Facades\DB;

class GlobalOptionsMigrate
{


    public static function run()
    {
        echo 'Конвертируем настройки' . PHP_EOL;
        $options = DB::connection('mysql_old')->table('global_option_models')->get();
        foreach ($options as $item) {
            $newItem = new GlobalOptionModel();
            $newItem->name = $item->name;
            $newItem->value = $item->value;
            $newItem->type = $item->type;
            $newItem->save();
        }
    }


}

class GlobalOptionModel extends GlobalOptionModelOriginal
{
    protected $casts = [];
}