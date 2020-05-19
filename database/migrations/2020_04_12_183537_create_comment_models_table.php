<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->comment('id пользователя');
            $table->integer('article_id')->comment('id статьи');
            $table->integer('comment_id')->nullable()->comment('id коментария на который отвечают');
            $table->text('message')->nullable()->comment('текст коментария');
            $table->softDeletes();
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
        Schema::dropIfExists('comment_models');
    }
}
