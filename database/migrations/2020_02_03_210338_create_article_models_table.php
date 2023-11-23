<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->string('title')->nullable();
            $table->string('uid')->nullable();
            $table->integer('user_id')->default(1)->comment('id автора статьи');
            $table->string('resume')->nullable();
            $table->text('text')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('public')->default(1);
            $table->boolean('news')->default(true);
            $table->boolean('allow_comments')->default(true);
            $table->dateTime('publish_time');
            $table->string('slug')->nullable()->unique()->comment('url статьи');
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
