<?php

namespace App\Console\Commands\Cron;

use App\Models\Settings\CameraModel;
use Illuminate\Console\Command;

class CronCommand extends Command
{
    /**
     * Имя и подпись консольной команды.
     *
     * @var string
     */
    protected $signature = 'cron:cron-start-actions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Команда для запуска по cron';

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
        echo 'Cron Action'. PHP_EOL;
        CameraModel::updateAllCache();

    }
}
