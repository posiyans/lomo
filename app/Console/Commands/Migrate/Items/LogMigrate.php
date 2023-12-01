<?php

namespace App\Console\Commands\Migrate\Items;

use App\Modules\Log\Models\LogModel;
use Illuminate\Support\Facades\DB;

class LogMigrate
{


    public static function run()
    {
        echo 'Конвертируем логи' . PHP_EOL;
        $atr = [
            'id',
            'user_id',
            'commentable_id',
            'commentable_type',
            'type',
            'action',
            'user_agent',
            'description',
            'value',
            'created_at',
            'updated_at',
        ];
        $soc = DB::connection('mysql_old')->table('logs')->get();
        foreach ($soc as $item) {
            $newItem = new LogModel();
            foreach ($atr as $key) {
                $newItem->$key = $item->$key;
            }
            $newItem->save();
        }
    }

}
