<?php

namespace App\Console\Commands;

use App\Models\Laratrust\Permission;
use App\Models\Laratrust\Role;
use App\Models\Owner\OwnerUserModel;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

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
//        Cache::tags('owner_user2')->flush();

        echo 'ТЕст!!!!!!';
        echo "\n";
    }

}
