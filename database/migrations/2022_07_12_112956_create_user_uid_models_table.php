<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserUidModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_uid_models', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('id пользователя');
            $table->string('uid')->comment('uid пользователя');
            $table->timestamps();
        });
        $users = \App\Modules\User\Models\UserModel::all();
        foreach ($users as $user) {
            $user_uid = new \App\Modules\User\Models\UserUidModel();
            $user_uid->user_id = $user->id;
            $user_uid->uid = \Ramsey\Uuid\Uuid::uuid4();
            $user_uid->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_uid_models');
    }
}
