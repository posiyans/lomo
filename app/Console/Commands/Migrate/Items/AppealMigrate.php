<?php

namespace App\Console\Commands\Migrate\Items;

use App\Modules\Appeal\Modules\AppealModel as AppealModelOriginal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AppealMigrate
{

    public static function run()
    {
        echo 'Конвертируем логи' . PHP_EOL;
        $atr = [
            'id',
            'user_id',
            'title',
            'text',
            'status',
            'date_close',
            'deleted_at',
            'created_at',
            'updated_at',
        ];

        $soc = DB::connection('mysql_old')->table('appeals')->get();
        foreach ($soc as $item) {
            $newItem = new AppealModel();
            foreach ($atr as $key) {
                $newItem->$key = $item->$key;
            }
            $newItem->appeal_type_id = 1;
            $newItem->uid = Str::uuid();
            $newItem->save();
        }
    }

}

class AppealModel extends AppealModelOriginal
{
    public $timestamps = false;
}