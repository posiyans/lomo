<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOptionsColumnToSteadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('steads', function (Blueprint $table) {
            $table->json('options')->after('discriptions')->nullable()->comment('опции для участка');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('steads', function (Blueprint $table) {
            $table->dropColumn('options');
        });
    }
}
