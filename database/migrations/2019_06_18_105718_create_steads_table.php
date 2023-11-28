<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->string('number')->unique();
            $table->string('size')->nullable();
//            $table->json('descriptions')->nullable();
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
