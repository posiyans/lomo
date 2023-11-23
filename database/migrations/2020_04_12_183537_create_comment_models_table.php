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
            $table->id();
            $table->string('uid')->comment('uid комментария');
            $table->integer('user_id')->comment('id пользователя');
            $table->string('commentable_type')->nullable();
            $table->string('commentable_uid')->nullable();
            $table->text('message')->nullable()->comment('текст коментария');
            $table->softDeletes();
            $table->integer('user_deletes_id')->nullable()->comment('id пользователя кто удалил');
            $table->json('options')->nullable()->comment('Доп опции');
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
