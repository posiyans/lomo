<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoceIdToInstrumentReadings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instrument_readings', function (Blueprint $table) {
            $table->integer('invoice_id')->after('value')->nullable();
            $table->integer('payment_id')->after('value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instrument_readings', function (Blueprint $table) {
            $table->dropColumn('invoice_id');
            $table->dropColumn('payment_id');
        });
    }
}
