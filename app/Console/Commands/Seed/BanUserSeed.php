<?php

namespace App\Console\Commands\Seed;

use App\Modules\BanUser\Models\BanUserModel;
use App\Modules\User\Models\UserModel;
use Illuminate\Console\Command;

class BanUserSeed extends Command
{
    /**
     * Имя и подпись консольной команды.
     *
     * @var string
     */
    protected $signature = 'seed:ban-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Добавить всех пользователей в бан';

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
        $users = UserModel::all();
        foreach ($users as $user) {
            $ban = new BanUserModel();
            $ban->user_id = $user->id;
            $ban->author_id = 1;
            $ban->save();
        }
    }
}
