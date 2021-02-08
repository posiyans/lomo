<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReceiptColumnToBillingReestrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billing_reestrs', function (Blueprint $table) {
//            $table->json('receipt')->after('type')->comment('на что назначались счета');
            $table->json('options')->after('type')->comment('параметры счета');
            $table->dropColumn('ratio_a');
            $table->dropColumn('ratio_b');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('billing_reestrs', function (Blueprint $table) {
//            $table->dropColumn('receipt');
            $table->dropColumn('options');
            $table->float('ratio_a')->nullable();
            $table->float('ratio_b')->nullable();
        });
    }
}
