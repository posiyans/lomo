<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_models', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable()->comment('заголовок');
            $table->string('uid')->nullable()->comment('uid');
            $table->integer('user_id')->nullable()->comment('id автора статьи');
            $table->string('resume')->nullable()->comment('краткий текст');
            $table->mediumText('text')->nullable()->comment('текст статьи');
            $table->integer('category_id')->nullable()->comment('id раздела');
            $table->integer('status')->default(1)->comment('статус статьи');
            $table->integer('allow_comments')->comment('возможность комментировать');
            $table->string('slug')->nullable()->unique()->comment('url статьи');
            $table->softDeletes();
            $table->timestamps();
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
        Schema::dropIfExists('article_models');
    }
}
