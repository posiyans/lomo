<?php

namespace App\Console\Commands\Migrate\Items;

use App\Models\Laratrust\Role;
use App\Modules\File\Classes\SaveFileForObjectClass;
use Http;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laratrust\Traits\LaratrustUserTrait;
use Str;

/**
 * конвертация поользователей
 *
 */
class UserMigrate
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
        echo 'Конвертируем users' . PHP_EOL;
        $users = DB::connection('mysql_old')->table('users')->get();
        foreach ($users as $item) {
            $newItem = new UserModel();
            foreach (self::$fields as $key => $value) {
                $newItem->$key = $item->$value;
            }
            $newItem->email = strtolower($item->email);
            $newItem->uid = Str::uuid();
            $newItem->save();
            if ($item->avatar) {
//                self::saveAvatar($newItem, $item->avatar);
            }
            if ($newItem->id == 1) {
                self::setRole($newItem, 'superAdmin');
            }
        }
//        dump($users);
    }

    private static function setRole($user, $roleName)
    {
        $role = Role::where('name', $roleName)->first();
        $user->syncRolesWithoutDetaching([$role->id]);
        DB::connection()->table('role_user')
            ->where('role_id', 1)
            ->where('user_id', 1)
            ->update(['user_type' => \App\Modules\User\Models\UserModel::class]);
//        $admin->user_type = \App\Modules\User\Models\UserModel::class;
//        $admin->save();
    }

    private static function saveAvatar($user, $avatar_url)
    {
        try {
            $response = Http::get($avatar_url);
            $tmpfname = tempnam(sys_get_temp_dir(), "user_avatar");
            $handle = fopen($tmpfname, "w");
            fwrite($handle, $response->body());
            fclose($handle);
            (new SaveFileForObjectClass($user))
                ->name('user_avatar_' . $user->id)
                ->size(filesize($tmpfname))
                ->description('avatar')
                ->type('image/jpeg')
                ->file($tmpfname)
                ->uploadUser($user)
                ->run();
        } catch (\Exception $e) {
        }
    }
}

class UserModel extends Model
{
    use LaratrustUserTrait;

    public $timestamps = false;
}