<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceRegisterModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_register', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('type_id')->comment('тип прибора');
            $table->integer('stead_id')->comment('к какому участку отностся');
            $table->integer('initial_data')->comment('начальные показания');
            $table->string('serial_number')->comment('серийный номер прибора');
            $table->string('device_brand')->comment('модель прибора');
            $table->date('installation_date')->nullable()->comment('дата установки');
            $table->date('verification_date')->nullable()->comment('дата до следующей поверки прибора');
            $table->string('descriptions')->nullable()->comment('коментарий');
            $table->boolean('active')->default(true)->comment('запрашивать показания');
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
        Schema::dropIfExists('device_register');
    }
}
