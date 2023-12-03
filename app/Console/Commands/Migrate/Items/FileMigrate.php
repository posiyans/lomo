<?php

namespace App\Console\Commands\Migrate\Items;

use App\Modules\Article\Models\ArticleModel;
use App\Modules\File\Classes\GetPathForHashClass;
use App\Modules\File\Models\FileModel as FileModelOriginal;
use Illuminate\Support\Facades\DB;
use Str;

/**
 * конвертация файлов
 *
 */
class FileMigrate
{
    private static $fields = [
        'id' => 'id',
        'user_id' => 'user_id',
        'commentable_uid' => 'commentable_uid',
        'hash' => 'hash',
        'name' => 'name',
        'size' => 'size',
        'description' => 'description',
        'deleted_at' => 'deleted_at',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
//        '' => '',
    ];


    public static function run()
    {
        echo 'Конвертируем файлов' . PHP_EOL;
        $categories = DB::connection('mysql_old')->table('files')->get();
        foreach ($categories as $item) {
            $newItem = new FileModel();
            foreach (self::$fields as $key => $value) {
                $newItem->$key = $item->$value;
            }
            $cType = $item->commentable_type;
            $cType = str_replace('App\Models\Article\ArticleModel', ArticleModel::class, $cType);
            $newItem->commentable_type = $cType;
            $newItem->type = self::getType($newItem->hash);
            $newItem->save();
        }
    }

    private static function getType($hash)
    {
        $path = realpath(__DIR__ . '/../../../../../storage/' . (new GetPathForHashClass($hash))->run());
//        dump($path, file_exists($path));
        if (file_exists($path)) {
            $type = mime_content_type($path);
        } else {
            $type = '';
        }
        return $type;
    }

}

class FileModel extends FileModelOriginal
{
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        $this->uid = Str::uuid();
        return parent::__construct($attributes);
    }
}