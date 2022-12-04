<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCommentModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('comment_models');
        Schema::create('comment_models', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->comment('uid комментария');
            $table->integer('user_id')->comment('id пользователя');
            $table->string('commentable_type')->nullable();
            $table->string('commentable_uid')->nullable();
            $table->text('message')->nullable()->comment('текст коментария');
            $table->softDeletes();
            $table->integer('user_deletes_id')->nullable()->comment('id пользователя кто удалил');
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
        Schema::create('comment_models', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('id пользователя');
            $table->integer('article_id')->comment('id статьи');
            $table->integer('comment_id')->nullable()->comment('id коментария на который отвечают');
            $table->text('message')->nullable()->comment('текст коментария');
            $table->softDeletes();
            $table->timestamps();
        });
    }
}
