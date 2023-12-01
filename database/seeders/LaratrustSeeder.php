<?php

namespace Database\Seeders;

use App\Models\Laratrust\Permission;
use App\Models\Laratrust\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Laratrust\Models\LaratrustPermission;
use Laratrust\Models\LaratrustRole;

class LaratrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        echo 'Roles seed!!!' . "\n";
        $this->truncateLaratrustTables();

        foreach ($this->roles as $key => $roleName) {
            $role = Role::where('name', $key)->first();
            if ($role) {
                echo 'Role ' . $roleName . " существует\n";
            } else {
                echo 'No role ' . $roleName . "\n";
                $admin = new Role();
                $admin->name = $key;
                $admin->display_name = $roleName;
                $admin->save();
            }
        }
        foreach ($this->permissions as $key => $value) {
            $role = Permission::where('name', $key)->first();
            if ($role) {
                echo 'Разрешение ' . $value . " существует\n";
            } else {
                echo 'No role ' . $value . "\n";
                $admin = new Permission();
                $admin->name = $key;
                $admin->display_name = $value;
                $admin->save();
            }
        }
        $user = User::find(1);
        if ($user) {
            $user->attachRole('superAdmin');
        }
    }


    protected $roles = [
        'superAdmin' => 'СуперАдмин',
//        'admin' => 'Администратор',
//        'president' => 'Председатель',
//        'curator' => 'Член правления',
//        'moderator' => 'Модератор',
//        'proprietor' => 'Собственник',
//        'gardening' => 'Член садоводства',
//        'user' => 'Подтвержденный пользователь',
//        'bookkeeper' => 'Бухгалтер'


    ];

    protected $permissions = [
        'send-mail-spam' => 'Email рассылка',

        'owner' => 'Собственник', // ?? или добавлять динамически
        'owner-show' => 'Просмотр собственников',
        'owner-edit' => 'Редактирование собстввенников',

        'stead-show' => 'Просмотр данных об участках',
        'stead-edit' => 'Редактирование данных об участках',

        'voting-show' => 'Просмотр данных голоосований',
        'voting-edit' => 'Редактирование голосований',

        'article-edit' => 'Редактирование статей',
        'article-show' => 'Просмотр полного списка статей',


        'role-show' => 'Просмотр ролей и прав',
        'role-edit' => 'Редактирование ролей и прав',

        'site-menu-edit' => 'Редактирование меню сайта',

        'user-ban-show' => 'Просмотр банов польльзователей',
        'user-ban-edit' => 'Ставить и снимать бан с польльзователей',

        'comment-edit' => 'Редактирование комментарии',
        'comment-ban' => 'Запрет на комментарии',
        'comment-delete' => 'Удаление коментариев',
        'access-admin-panel' => 'Доступ в админ панель',

        'appeal-show' => 'Просмотр обращений',
        'appeal-edit' => 'Обработка обращений',

        'user-edit' => 'Редактирование пользователей',
        'user-show' => 'Просмотр пользователей',

        'settings-gardening' => 'Редактирование данных садоводства',
        'settings-rate' => 'Редактирование тарифов',
        'settings-camera' => 'Настройка камер',

        'bookkeeping-show' => 'Доступ для бухгалтерию',
        'profit-show' => 'Просмотр начислений',
        'profit-edit' => 'Редактирование начислений',
        'payment-show' => 'Просмотр платежей',
        'payment-edit' => 'Редактирование платежей',

//        'access-to-personal' => 'Доступ к персональным данным',
//        'write-personal-data' => 'Изменение персональных данных'
    ];

    private function truncateLaratrustTables()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('permission_role')->truncate();
        DB::table('permission_user')->truncate();
        DB::table('role_user')->truncate();
        LaratrustRole::truncate();
        LaratrustPermission::truncate();
        Schema::enableForeignKeyConstraints();
    }
}
