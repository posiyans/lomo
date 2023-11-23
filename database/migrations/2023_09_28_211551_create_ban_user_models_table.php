<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanUserModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ban_user_models', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid');
            $table->bigInteger('user_id');
            $table->string('commentable_type')->nullable();
            $table->string('commentable_uid')->nullable();
            $table->dateTime('end_ban_time')->nullable();
            $table->bigInteger('author_id');
            $table->string('description')->nullable();
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
        Schema::dropIfExists('ban_user_models');
    }
}
