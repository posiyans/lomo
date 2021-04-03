<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillingReestrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_reestrs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('type');
            $table->float('ratio_a');
            $table->float('ratio_b');
            $table->integer('user_id');
            $table->json('history');
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
        Schema::dropIfExists('billing_reestrs');
    }
}
