<?php

namespace App\Console\Commands;

use App\Models\Laratrust\Role;
use App\Modules\User\Repositories\UserRepository;
use Illuminate\Console\Command;

class SetRoleForUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:set-role-for-user {role} {user_email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Установить роль для пользователя';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->hasArgument('role') && $this->hasArgument('user_email')) {
            try {
                $user_email = $this->argument('user_email');
                $user = (new UserRepository())->byEmail($user_email)->run();
                $role = Role::where('name', $this->argument('role'))->first();
                if ($role) {
                    // todo поверить
                    $user->syncRolesWithoutDetaching([$role->id]);
                    echo "Ok";
                    echo PHP_EOL;
                    return '';
                }
//            $permission = Permission::where('name', $this->argument('role'))->first();
//            if ($permission) {
//                $user->attachPermission($permission);
//                echo "Ok \n";
//                return '';
//            }
                echo 'Роль не найдена';
                echo "\n";
            } catch (\Exception $e) {
                echo $e->getMessage();
                echo PHP_EOL;
            }
            echo 'Укажите роль и id пользователя';
            echo "\n";
        }
    }
}
