<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * @deprecated
 */
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
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('date_publish');
            $table->date('date_start')->nullable()->comment('время начала');
            $table->date('date_stop')->nullable()->comment('время скончания');
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
