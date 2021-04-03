<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillingPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('stead_id');
            $table->string('discription')->nullable();
            $table->integer('type');
            $table->float('price');
            $table->string('transaction')->nullable();
            $table->dateTime('payment_date');
            $table->integer('reestr_id');
            $table->integer('payment_type');
            $table->json('raw_data');
            $table->integer('invoice_id')->nullable();
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('billing_payments');
    }
}
