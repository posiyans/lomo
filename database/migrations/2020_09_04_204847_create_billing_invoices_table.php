<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillingInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->integer('stead_id');
            $table->integer('type');
            $table->integer('reestr_id')->nullable();
            $table->float('price');
            $table->integer('payment_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('discription')->nullable();
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
        Schema::dropIfExists('billing_invoices');
    }
}
