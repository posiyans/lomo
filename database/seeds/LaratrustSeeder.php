<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Role;
use App\Permission;
use App\User;

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
        foreach ($this->roles as $key=>$roleName) {
            $role = Role::where('name', $key)->first();
            if ($role) {
                echo 'Role ' . $roleName . " существует\n";
            } else {
                echo 'No role ' . $roleName . "\n";
                $admin = new Role();
                $admin->name         = $key;
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
        if ($user && $role){
            $user->syncRolesWithoutDetaching(['superAdmin', 'admin']);
            //$user->attachRole($role);
//            $user->attachRole('admin');
        }
    }


    protected $roles = [
        'superAdmin' => 'СуперАдмин',
        'admin' => 'Администратор',
        'president' => 'Председатель',
        'curator' => 'Член правления',
        'moderator' => 'Модератор',
        'proprietor' => 'Собственник',
        'gardening' => 'Член садоводства',
        'user' => 'Подтвержденный пользователь',
        'bookkeeper'=> 'Бухгалтер'


    ];

    protected $permissions = [
        'gardening-edit' => 'Правка данных садоводства',
        'send-mail-spam' => 'Email рассылка',
        'сreate-polls' => 'Создание голосований',
        'edit-stead' => 'Правка данных об участках',
        'edit-role' => 'Редактирование ролей и прав',
        'create-article' => 'Создание статей',
        'create-comment' => 'Создание коментарии',
        'ban-comment' => 'Запрет на коментарии',
        'delete-comment' => 'Удаление коментариев',
        'access-admin-panel' => 'Доступ в админ панель',
        'show-appels' => 'Просмотр обращений',
        'edit-appels' => 'Обработка обращений',
        'edit-users' => 'Редактирование пользователей',
        'show-users' => 'Просмотр пользователей',
        'edit-rate' => 'Правка тарифов',
        'edit-menu' => 'Редактирование меню',
        'reestr-edit' => 'Редактирование начислений'






    ];
}
