<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVotingFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voting_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned()->comment('кто загрузил');
            $table->integer('voting_id')->nullable()->comment('id голосования');
            $table->integer('stead_id')->nullable()->comment('id голосования');
            $table->string('hash')->nullable();
            $table->text('name')->nullable();
            $table->integer('size')->nullable();
            $table->text('discription')->nullable();
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
        Schema::dropIfExists('voting_files');
    }
}
