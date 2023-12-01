<?php

namespace App\Console\Commands\Migrate\Items;

use App\Modules\Gardening\Actions\UpdateGardeningFieldAction;
use App\Modules\Gardening\Repositories\GardeningRepository;
use Illuminate\Support\Facades\DB;

class GardeningMigrate
{


    public static function run()
    {
        echo 'Конвертируем реквизиты' . PHP_EOL;
        $gardening = DB::connection('mysql_old')->table('gardenings')->first();
        $fields = (new GardeningRepository())->getKeys();
        foreach ($fields as $key) {
            $value = $gardening->$key;
            (new UpdateGardeningFieldAction($key))->value($value)->run();
        }
    }

}
