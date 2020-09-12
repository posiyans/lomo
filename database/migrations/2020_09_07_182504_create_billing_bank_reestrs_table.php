<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillingBankReestrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_bank_reestrs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('data');
            $table->integer('user_id');
            $table->string('file_hash');
            $table->text('file_name')->nullable();
            $table->integer('file_size')->nullable();
            $table->boolean('file_publish')->default(false);
            $table->softDeletes();
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
        Schema::dropIfExists('billing_bank_reestrs');
    }
}
