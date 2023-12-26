<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate_models', function (Blueprint $table) {
            $table->id();
            $table->integer('device_id');
            $table->string('ratio_a');
            $table->string('ratio_b');
            $table->string('description')->nullable();
            $table->date('date_start')->nullable()->comment('дата начала действия тарифа');
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
        Schema::dropIfExists('rates');
    }
}
