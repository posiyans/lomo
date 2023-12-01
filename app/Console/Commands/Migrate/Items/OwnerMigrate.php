<?php

namespace App\Console\Commands\Migrate\Items;

use App\Models\Owner\OwnerUserModel;
use App\Modules\Owner\Models\OwnerUserSteadModel;
use App\Modules\Owner\Models\OwnerUserValueModel as OwnerUserValueModelOriginal;
use Illuminate\Support\Facades\DB;

/**
 * конвертация поользователей
 *
 */
class OwnerMigrate
{
    private static $fields = [
        'id' => 'id',
        'name' => 'name',
        'email_verified_at' => 'email_verified_at',
        'password' => 'password',
        'remember_token' => 'remember_token',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
        'last_name' => 'last_name',
        'middle_name' => 'middle_name',
        'adres' => 'adres',
        'phone' => 'phone',
        'last_connect' => 'last_connect',
//        '' => '',
    ];


    public static function run()
    {
        echo 'Конвертируем owner' . PHP_EOL;
        $users = DB::connection('mysql_old')->table('owner_user_models')->where('deleted_at', null)->get();
        foreach ($users as $item) {
            $newItem = new OwnerUserModel();
            $newItem->uid = $item->uid;
            $newItem->save();
        }
        $values = DB::connection('mysql_old')->table('owner_user_value_models')->get();
        foreach ($values as $item) {
            $newItem = new OwnerUserValueModel();
            $newItem->id = $item->id;
            $newItem->uid = $item->uid;
            $newItem->property = $item->property;
            $newItem->value = $item->value;
            $newItem->deleted_at = $item->deleted_at;
            $newItem->created_at = $item->created_at;
            $newItem->updated_at = $item->updated_at;
            $newItem->save();
        }
        $steads = DB::connection('mysql_old')->table('owner_user_stead_models')->get();
        foreach ($steads as $item) {
            $newItem = new OwnerUserSteadModel();
            $newItem->id = $item->id;
            $newItem->owner_uid = $item->owner_uid;
            $newItem->stead_id = $item->stead_id;
            $newItem->proportion = $item->proportion;
            $newItem->deleted_at = $item->deleted_at;
            $newItem->created_at = $item->created_at;
            $newItem->updated_at = $item->updated_at;
            $newItem->save();
        }
//        dump($users);
    }


}

class OwnerUserValueModel extends OwnerUserValueModelOriginal
{
    protected $casts = [];
    public $timestamps = false;
}