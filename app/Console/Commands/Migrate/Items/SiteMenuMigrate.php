<?php

namespace App\Console\Commands\Migrate\Items;

use App\Modules\SiteMenu\Models\SiteMenuModel;
use Illuminate\Support\Facades\DB;

/**
 * конвертация меню
 *
 */
class SiteMenuMigrate
{


    public static function run()
    {
        echo 'Конвертируем меню' . PHP_EOL;
        $categories = DB::connection('mysql_old')->table('category_models')->get();
        foreach ($categories as $item) {
            $newItem = new SiteMenuModel();
            $newItem->id = $item->id;
            $newItem->label = $item->name;
            $newItem->parent = $item->parent;
            $newItem->sort = $item->position;
            $newItem->path = $item->menu_name;
            $newItem->save();
        }
    }

}
