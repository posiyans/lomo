<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiptTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->boolean('auto_invoice')->default(false)->comment('автоматическое выставление счета');
            $table->json('options')->nullable()->comment('доп опции');
            $table->integer('depends')->default(0);
            $table->integer('payment_period')->nullable()->comment('период оплаты');
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
        Schema::dropIfExists('receipt_types');
    }
}
