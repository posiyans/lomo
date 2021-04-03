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
            $table->string('resume')->nullable();
            $table->text('text')->nullable();
            $table->integer('category_id')->nullable();
            $table->boolean('public')->default(true);
            $table->boolean('news')->default(true);
            $table->boolean('allow_comments')->default(true);
            $table->dateTime('publish_time');

            $table->timestamps();
        });
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
