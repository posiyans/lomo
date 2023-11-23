<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * @deprecated
 */
class CreateInstrumentReadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instrument_readings', function (Blueprint $table) {
            $table->id();
            $table->integer('stead_id');
            $table->integer('device_id');
            $table->string('instrument_serial')->nullable();
            $table->string('value');
            $table->integer('invoice_id')->after('value')->nullable();
            $table->integer('payment_id')->after('value')->nullable();
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
        Schema::dropIfExists('instrument_readings');
    }
}
