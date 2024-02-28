<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFieldModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_field_models', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->comment('user id');
            $table->string('property')->comment('свойство');
            $table->string('type')->comment('тип значения');
            $table->json('value')->nullable()->comment('значение');
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
        Schema::dropIfExists('user_field_models');
    }
}
