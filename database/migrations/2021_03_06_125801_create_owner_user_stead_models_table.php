<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOwnerUserSteadModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('owner_user_model_stead_model', function (Blueprint $table) {
            $table->id();
            $table->string('owner_uid')->comment('uid собственника');
            $table->integer('stead_id')->comment('id участка');
            $table->integer('proportion')->default('100')->comment('Доля собственности');;
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
        Schema::dropIfExists('owner_user_stead_models');
    }
}
