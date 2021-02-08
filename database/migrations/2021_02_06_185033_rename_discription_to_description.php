<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameDiscriptionToDescription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('steads', function (Blueprint $table) {
            $table->renameColumn('discriptions', 'descriptions');
        });
        Schema::table('metering_devices', function (Blueprint $table) {
            $table->renameColumn('discription', 'description');
        });
        Schema::table('rates', function (Blueprint $table) {
            $table->renameColumn('discription', 'description');
        });
        Schema::table('files', function (Blueprint $table) {
            $table->renameColumn('discription', 'description');
        });
        Schema::table('billing_invoices', function (Blueprint $table) {
            $table->renameColumn('discription', 'description');
        });
        Schema::table('billing_payments', function (Blueprint $table) {
            $table->renameColumn('discription', 'description');
        });
        Schema::table('voting_files', function (Blueprint $table) {
            $table->renameColumn('discription', 'description');
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
            $table->renameColumn('descriptions', 'discriptions');
        });
        Schema::table('metering_devices', function (Blueprint $table) {
            $table->renameColumn('description', 'discription');
        });
        Schema::table('rates', function (Blueprint $table) {
            $table->renameColumn('description', 'discription');
        });
        Schema::table('files', function (Blueprint $table) {
            $table->renameColumn('description', 'discription');
        });
        Schema::table('billing_invoices', function (Blueprint $table) {
            $table->renameColumn('description', 'discription');
        });
        Schema::table('billing_payments', function (Blueprint $table) {
            $table->renameColumn('description', 'discription');
        });
        Schema::table('voting_files', function (Blueprint $table) {
            $table->renameColumn('description', 'discription');
        });
    }
}
