<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColummToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('avatar')->nullable();
            $table->json('steads_id')->nullable();
            $table->string('adres')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('consent_to_email')->default(false);
            $table->boolean('consent_personal')->default(false);
            $table->dateTime('last_connect')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_name');
            $table->dropColumn('middle_name');
            $table->dropColumn('avatar');
            $table->dropColumn('steads_id');
            $table->dropColumn('adres');
            $table->dropColumn('phone');
            $table->dropColumn('consent_to_email');
            $table->dropColumn('consent_personal');
            $table->dropColumn('last_connect');
        });
    }
}
