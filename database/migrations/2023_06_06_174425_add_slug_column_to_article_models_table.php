<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugColumnToArticleModelsTable extends Migration
{
    /**
     * слаг для адреса статьи
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article_models', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->comment('url статьи');
        });
        $articles = \App\Modules\Article\Models\ArticleModel::all();
        foreach ($articles as $article) {
            $article->slug = substr(Str::slug($article->title), 0, 240);
            try {
                $article->save();
            } catch (\Exception $e) {
                $article->slug = $article->slug . '-' . substr($article->uid, 0, 4);
                try {
                    $article->save();
                } catch (\Exception $e) {
                    $article->slug = $article->slug . '-' . substr(Str::uuid(), 0, 4);
                    $article->save();
                }
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
        Schema::table('article_models', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
