<?php

namespace App\Console\Commands\Migrate\Items;

use App\Modules\Social\Models\LinkedSocialAccounts;
use Illuminate\Support\Facades\DB;

class SocialMigrate
{


    public static function run()
    {
        echo 'Конвертируем настройки' . PHP_EOL;
        $soc = DB::connection('mysql_old')->table('linked_social_accounts')->get();
        foreach ($soc as $item) {
            $newItem = new LinkedSocialAccounts();
            $newItem->provider_name = $item->provider_name;
            $newItem->user_id = $item->user_id;
            $newItem->provider_id = $item->provider_id;
            $newItem->created_at = $item->created_at;
            $newItem->updated_at = $item->updated_at;
            $newItem->save();
        }
    }

}
