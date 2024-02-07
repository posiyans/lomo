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
        Schema::create('metering_device_models', function (Blueprint $table) {
            $table->id();
            $table->integer('stead_id')->comment('к какому участку отностся');
            $table->integer('rate_type_id')->comment('к какому тирафа относится прибор');
            $table->integer('initial_data')->comment('начальные показания');
            $table->string('description')->nullable()->comment('комментарий');
            $table->json('options')->comment('данные прибора (серийный номер прибора, модель прибора, дата установки, дата до следующей поверки прибора)');
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
        Schema::dropIfExists('metering_device_models');
    }
}
