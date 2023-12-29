<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingInvoiceGroupModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_invoice_group_models', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('назначение платежа');
            $table->integer('rate_group_id')->nullable()->comment('группа тарифов');
            $table->json('options')->comment('параметры счета');
            $table->integer('user_id')->nullable()->comment('Кто выставил счета');
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
        Schema::dropIfExists('billing_invoice_group_models');
    }
}
