<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_payment_models', function (Blueprint $table) {
            $table->id();
            $table->integer('stead_id')->nullable()->comment('id участка');
            $table->integer('rate_group_id')->nullable()->comment('группа платежа');
            $table->float('price');
            $table->dateTime('payment_date')->comment('дата платежа');
            $table->integer('payment_type')->comment('тип платежа (безнал, нал)');
            $table->json('raw_data');
            $table->integer('invoice_id')->nullable()->comment('счет на оплату');
            $table->integer('user_id')->nullable()->comment('кто занес платеж в систему');
            $table->boolean('error')->default(false)->comment('есть ли неточности в строке');
            $table->text('description')->nullable();
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
        Schema::dropIfExists('billing_payment_models');
    }
}
