<?php

namespace App\Console\Commands;

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Console\Command;

class SetRoleForUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:set-role-for-user {role} {user}';

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
        if ($this->hasArgument('role') && $this->hasArgument('user')){
            $user = User::find($this->argument('user'));
            if ($user){
                $role = Role::where('name', $this->argument('role'))->first();
                if ($role){
                    $user->attachRole($role);
                    echo "Ok \n";
                    return '';
                }
                $permission = Permission::where('name',  $this->argument('role'))->first();
                if ($permission){
                    $user->attachPermission($permission);
                    echo "Ok \n";
                    return '';
                }
                echo 'Роль не найдена';
                echo "\n";
            }
        }
        echo 'Укажите роль и id пользователя';
        echo "\n";
    }
}
