<?php

namespace App\Console\Commands\Migrate;

use App\Console\Commands\Migrate\Items\AppealMigrate;
use App\Console\Commands\Migrate\Items\ArticleMigrate;
use App\Console\Commands\Migrate\Items\FileMigrate;
use App\Console\Commands\Migrate\Items\GardeningMigrate;
use App\Console\Commands\Migrate\Items\GlobalOptionsMigrate;
use App\Console\Commands\Migrate\Items\OwnerMigrate;
use App\Console\Commands\Migrate\Items\SiteMenuMigrate;
use App\Console\Commands\Migrate\Items\SocialMigrate;
use App\Console\Commands\Migrate\Items\SteadMigrate;
use App\Console\Commands\Migrate\Items\UserMigrate;
use Illuminate\Console\Command;

/**
 * конвертация страй базы
 * php artisan migrate:fresh --seed && php artisan command:convert
 */
class MasterMigrate extends Command
{
    /**
     * Имя и подпись консольной команды.
     *
     * @var string
     */
    protected $signature = 'command:convert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Конвертировать старую базу';

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
        echo 'Конвертируем' . PHP_EOL;
//        LogMigrate::run();
        AppealMigrate::run();
        FileMigrate::run();
        SteadMigrate::run();
        UserMigrate::run();
        ArticleMigrate::run();
        SiteMenuMigrate::run();
        GardeningMigrate::run();
        GlobalOptionsMigrate::run();
        SocialMigrate::run();
        OwnerMigrate::run();

//        $users = DB::connection('mysql_old')->table('users')->get();
//        dump($users);
    }
}
