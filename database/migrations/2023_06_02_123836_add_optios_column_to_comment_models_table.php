<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOptiosColumnToCommentModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comment_models', function (Blueprint $table) {
            $table->json('options')->nullable()->comment('Доп опции');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comment_models', function (Blueprint $table) {
            $table->dropColumn('options');
        });
    }
}
