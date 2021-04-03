<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_sms', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable()->comment('кому отправленно');
            $table->integer('sender_id')->nullable()->comment('кто отправлял');
            $table->string('phone')->comment('номер телефона');
            $table->text('text')->comment('текст смс');
            $table->string('type')->comment('тип смс');
            $table->string('gateway')->nullable()->comment('шлюз отправки');
            $table->string('price')->nullable()->comment('цена смс');
            $table->string('sms_id')->nullable()->comment('идентификатор смс на шлюзе');
            $table->string('status')->nullable()->comment('статус смс');
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
        Schema::dropIfExists('notification_sms');
    }
}
