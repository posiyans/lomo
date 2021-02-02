<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAutoInvoiceColumnToReceiptTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receipt_types', function (Blueprint $table) {
            $table->boolean('auto_invoice')->after('name')->default(false)->comment('автоматическое выставление счета');
            $table->json('options')->after('auto_invoice')->nullable()->comment('опции для участка');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receipt_types', function (Blueprint $table) {
            $table->dropColumn('auto_invoice');
            $table->dropColumn('options');
        });
    }
}
