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
        $exclude = [];
        foreach ($categories as $item) {
            if ($item->parent) {
                $exclude[] = $item->parent;
            }
        }
        foreach ($categories as $item) {
            $newItem = new SiteMenuModel();
            $newItem->id = $item->id;
            $newItem->label = $item->name;
            $newItem->parent = $item->parent;
            $newItem->sort = $item->position;
            $path = $item->menu_name;
            if ($path) {
                $path = str_replace('/modules/camera', '/camera/show', $path);
                $path = str_replace('/modules/weather', '/weather/show', $path);
                $path = str_replace('/modules/schedule', '/yandex/schedule', $path);
            } else {
                $path = '/article/list/' . $item->id;
            }
            if (in_array($newItem->id, $exclude)) {
                $path = '';
            }

            $newItem->path = $path;
            $newItem->save();
        }
    }

}
