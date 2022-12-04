<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixArticleModelInFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $qr = "UPDATE `files` SET `commentable_type` = 'App\\Modules\\Article\\Models\\ArticleModel' WHERE commentable_type IN ('App\\Models\\Article\\ArticleModel')";
        echo $qr;
        echo PHP_EOL;
        \Illuminate\Support\Facades\DB::statement($qr);
        $files = \App\Modules\File\Models\FileModel::all();

        foreach ($files as $file) {
            if (!$file->uid) {
                $s= "UPDATE `files` set  `uid` = '" . \Ramsey\Uuid\Uuid::uuid4() . "' where `id` = " . $file->id . ";";
                echo $s;
                echo PHP_EOL;
                \Illuminate\Support\Facades\DB::statement($s);
//                $path = (new \App\Modules\File\Classes\GetPathForHashClass($file->hash))->run();
//                // todo обработать тип файла
//                if (file_exists($path)) {
//                    echo mime_content_type($path) . "\n";
//                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::statement("UPDATE `files` set `commentable_type` = 'App\\Models\\Article\\ArticleModel' where commentable_type IN ('App\\Modules\\Article\\Models\\ArticleModel')");

    }
}
