<?php

namespace App\Console\Commands;

use App\Models\Laratrust\Permission;
use App\Models\Laratrust\Role;
use App\Models\Owner\OwnerUserModel;
use App\Models\User;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Течст!!!!!!!!!!!!';

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
        $this->add();
//        $user = OwnerUserModel::where('fio', 'Иванов Иван Иванович')->first();
        $user = OwnerUserModel::find(1);
        $user = OwnerUserModel::where('email', 'posiyans@yandex.ru')->first();
        if ($user) {

            print_r($user->fio);
            echo "\n";
            print_r($user->email);
            echo "\n";
        } else {
            echo 'no found';
        echo "\n";
        }
        echo 'ТЕст!!!!!!';
        echo "\n";
    }

    public function add()
    {
        $user = new OwnerUserModel();
        $user->stead_id = 1;
        $user->proportion = 100;
        $user->uid = 'sdfsdfsdfs';
        $user->fio = 'Иванов Иван Иванович';
        $user->email = 'posiyans@yandex.ru';
        $user->phone = '+79119612747';
        $user->save();
    }
}
