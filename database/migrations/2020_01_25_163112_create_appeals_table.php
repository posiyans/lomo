<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appeal_type_models', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullable()->comment('тип обращения');
            $table->boolean('public')->default(true)->comment('Доступен пользователю');
            $table->string('description')->nullable()->comment('Описание типа');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('appeal_models', function (Blueprint $table) {
            $table->id();
            $table->string('uid');
            $table->integer('user_id')->unsigned()->comment('Автор обращения');
            //$table->foreign('user_id')->references('id')->on('users');
            $table->string('title')->comment('Заголовок обращения');
            $table->mediumText('text')->nullable()->comment('Текст обращения');
            $table->integer('appeal_type_id')->nullable()->comment('тип обращения');
            $table->string('status')->default('open')->comment('Статус обращения');
            $table->integer('close_user_id')->nullable()->comment('Кто закрыл обращение');
            $table->dateTime('date_close')->nullable()->comment('Время закрытия обращения');
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
        Schema::dropIfExists('appeals');
    }
}
