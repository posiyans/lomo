<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceRegisterModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_register_models', function (Blueprint $table) {
            $table->id();
            $table->integer('rate_type_id')->comment('к какому типу тирафа относится прибор');
            $table->integer('stead_id')->comment('к какому участку отностся');
            $table->integer('initial_data')->comment('начальные показания');
            $table->string('serial_number')->comment('серийный номер прибора');
            $table->string('device_brand')->comment('модель прибора');
            $table->date('installation_date')->nullable()->comment('дата установки');
            $table->date('verification_date')->nullable()->comment('дата до следующей поверки прибора');
            $table->string('descriptions')->nullable()->comment('комментарий');
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
        Schema::dropIfExists('device_register_models');
    }
}
