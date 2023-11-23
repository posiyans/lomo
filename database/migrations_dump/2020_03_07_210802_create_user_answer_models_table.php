<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAnswerModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_answer_models', function (Blueprint $table) {
            $table->id();
            $table->integer('question_id')->comment('id вопроса');
            $table->integer('answer_id')->comment('id ответа');
            $table->integer('user_id')->comment('id пользователя');
            $table->integer('stead_id')->nullable()->comment('id участка');
            $table->integer('power_of_attorney_id')->nullable()->comment('id доверенности');
            $table->boolean('not_valid')->default(false)->comment('недествительный');
            $table->text('description')->nullable()->comment('описание');
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
        Schema::dropIfExists('user_answer_models');
    }
}
