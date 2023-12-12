<?php

namespace App\Console\Commands\Migrate\Items;

use App\Modules\Article\Models\ArticleModel;
use App\Modules\Article\Models\CategoryModel;
use Illuminate\Support\Facades\DB;
use Str;

/**
 * конвертация статей
 *
 */
class ArticleMigrate
{
    private static $fields = [
        'id' => 'id',
        'uid' => 'uid',
        'title' => 'title',
        'resume' => 'resume',
        'category_id' => 'category_id',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
    ];


    public static function run()
    {
        echo 'Конвертируем article' . PHP_EOL;
        $categories = DB::connection('mysql_old')->table('category_models')->where('menu_name', null)->get();
        foreach ($categories as $item) {
            $newItem = new CategoryModel();
            $newItem->id = $item->id;
            $newItem->name = $item->name;
            $newItem->parent = $item->parent;
            $newItem->position = $item->position;
            $newItem->save();
        }
        $files = \App\Modules\File\Models\FileModel::all();
        $rep = [];
        foreach ($files as $file) {
            $rep[$file->id] = $file->uid;
        }
        $articles = DB::connection('mysql_old')->table('article_models')->get();
        foreach ($articles as $item) {
            $newItem = new ArticleModel();
            foreach (self::$fields as $key => $value) {
                $newItem->$key = $item->$value;
            }
            $text = $item->text;
            foreach ($rep as $k => $v) {
                $f = '/api/user/storage/file/' . $k . '"';
                $r = '/api/v2/file/get?uid=' . $v . '"';
                $text = str_replace($f, $r, $text);
            }
            $newItem->text = $text;
            $newItem->allow_comments = 1;
            $newItem->status = $item->public + 1;
            $newItem->slug = substr(Str::slug($newItem->title), 0, 240);


            try {
                $newItem->save();
            } catch (\Exception $e) {
                $newItem->slug = $newItem->slug . '-' . substr($newItem->uid, 0, 4);
                try {
                    $newItem->save();
                } catch (\Exception $e) {
                    $newItem->slug = $newItem->slug . '-' . substr(Str::uuid(), 0, 4);
                    $newItem->save();
                }
            }
        }
    }

}
