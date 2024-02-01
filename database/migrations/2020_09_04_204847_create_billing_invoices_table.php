<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_invoice_models', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('назначение счета');
            $table->integer('stead_id');
            $table->integer('rate_group_id');
            $table->integer('invoice_group_id')->nullable();
            $table->float('price');
            $table->boolean('is_paid')->default(false)->comment('оплачен');
            $table->integer('user_id')->nullable()->comment('кто выставил счет');
            $table->json('options')->nullable()->comment('расшифровка счета');
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
        Schema::dropIfExists('billing_invoice_models');
    }
}
