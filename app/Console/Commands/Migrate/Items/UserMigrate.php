<?php

namespace App\Console\Commands\Migrate\Items;

use App\Models\Laratrust\Role;
use App\Modules\File\Classes\SaveFileForObjectClass;
use App\Modules\User\Models\UserFieldModel;
use App\Modules\User\Repositories\GetUserByUidRepository;
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
            $newItem->options = [];
            $newItem->save();
            $primaryUser = (new GetUserByUidRepository($newItem->id))->run();

            $primaryUser->setField('adres', $item->adres);
            $primaryUser->setField('phone', $item->phone);
            if ($item->avatar) {
                self::saveAvatar($primaryUser, $item->avatar);
            }
            if ($newItem->id == 1) {
                self::setRole($primaryUser, 'superAdmin');
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

    protected $casts = [
        'options' => 'array',
    ];

    public $timestamps = false;

    public function setField($name, $value)
    {
        $field_model = UserFieldModel::where('user_id', $this->id)->where('property', $name)->first();
        if (!$field_model) {
            $field_model = new UserFieldModel();
            $field_model->property = $name;
            $field_model->user_id = $this->id;
        }
        $type = 'string';
        $val = [$value];
        if (is_array($value)) {
            $type = 'array';
            $val = $value;
        } elseif (is_bool($value)) {
            $type = 'bool';
        } elseif (is_numeric($value)) {
            $type = 'numeric';
        }
        $field_model->value = $val;
        $field_model->type = $type;
        $field_model->save();
    }
}