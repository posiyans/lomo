<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateInstrumentReadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instrument_reading_models', function (Blueprint $table) {
            $table->id();
            $table->integer('metering_device_id')->nullable()->comment('id прибора');
            $table->float('value')->comment('значение показаний');
            $table->integer('invoice_id')->nullable();
            $table->integer('payment_id')->nullable();
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
        Schema::dropIfExists('instrument_reading_models');
    }
}
