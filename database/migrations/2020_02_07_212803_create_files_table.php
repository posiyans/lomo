<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_models', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->comment('uid файла');
            $table->integer('user_id')->unsigned();
//            $table->foreign('user_id')->references('id')->on('users');
            $table->string('commentable_type')->nullable();
            $table->string('commentable_uid')->nullable();
            $table->string('hash')->nullable()->comment('хеш файла');
            $table->text('name')->nullable()->comment('имя файла');
            $table->string('type')->comment('тип файла');
            $table->integer('size')->nullable()->comment('размер файла');
            $table->text('description')->nullable()->comment('доп. информация');
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
        Schema::dropIfExists('files');
    }
}
