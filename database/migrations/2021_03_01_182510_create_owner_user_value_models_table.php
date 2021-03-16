<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOwnerUserValueModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('owner_user_value_models', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->comment('uid');
            $table->string('property')->comment('свойство');
            $table->text('value')->nullable()->default(null)->comment('значение');
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
        Schema::dropIfExists('owner_user_value_models');
    }
}
