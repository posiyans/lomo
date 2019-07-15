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
            $table->bigIncrements('id');
            $table->integer('gardening_id');
            $table->string('number');
            $table->string('surname')->nullable();
            $table->string('name')->nullable();
            $table->string('patronymic')->nullable();
            $table->string('size')->nullable();
            $table->string('email')->nullable();
            $table->text('history')->nullable();
            $table->json('discriptions')->nullable();
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
