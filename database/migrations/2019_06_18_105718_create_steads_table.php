<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSteadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steads', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->string('size')->nullable();
            $table->json('descriptions')->nullable();
            $table->json('options')->nullable()->comment('опции для участка');
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
        Schema::dropIfExists('steads');
    }
}
