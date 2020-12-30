<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullableTypeForSteadIdAndTypeInBillingPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billing_payments', function (Blueprint $table) {
            $table->integer('stead_id')->nullable()->comment('id участка')->change();
            $table->integer('type')->nullable()->comment('тип платежа')->change();
            $table->integer('reestr_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
