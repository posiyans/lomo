<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotingModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voting_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('date_publish');
            $table->dateTime('date_start');
            $table->dateTime('date_stop');
            $table->string('type')->default(1);
            $table->integer('comments')->default(1);
            $table->string('status')->default('new');
            $table->json('answer')->nullable();
            $table->string('result')->nullable();
            $table->string('uid')->nullable();
            $table->boolean('public')->default(false);
            $table->softDeletes();
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
        Schema::dropIfExists('voting_models');
    }
}
