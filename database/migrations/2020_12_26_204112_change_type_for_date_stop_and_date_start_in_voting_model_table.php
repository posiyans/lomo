<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypeForDateStopAndDateStartInVotingModelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('voting_models', function (Blueprint $table) {
            $table->date('date_start')->nullable()->comment('время начала')->change();
            $table->date('date_stop')->nullable()->comment('время скончания')->change();
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
