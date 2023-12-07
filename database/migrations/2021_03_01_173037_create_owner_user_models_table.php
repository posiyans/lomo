<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOwnerUserModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('owner_user_models', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->comment('uid');
            $table->string('user_uid')->nullable()->comment('uid пользователя');
            $table->integer('sort')->nullable()->comment('сортировка списка');
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
        Schema::dropIfExists('owner_user_models');
    }
}
