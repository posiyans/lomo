<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddErrorColumnToBillingPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billing_payments', function (Blueprint $table) {
            $table->boolean('error')->default(false)->after('user_id')->comment('есть ли неточности в строке');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('billing_payments', function (Blueprint $table) {
            $table->dropColumn('error');
        });
    }
}
