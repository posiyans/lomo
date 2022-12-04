<?php

namespace App\Console\Commands\Migrate;

use App\Models\Stead;
use App\Models\Voting\AnswerModel;
use App\Models\Voting\UserAnswerModel;
use App\Models\Voting\VotingModel;
use App\Modules\File\Classes\SaveFileForObjectClass;
use App\Modules\User\Models\UserModel;
use Illuminate\Console\Command;

class AvatarMigrate extends Command
{
    /**
     * Имя и подпись консольной команды.
     *
     * @var string
     */
    protected $signature = 'command:avatar-migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Конвертировать аватар';

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
            $avatar = $user->avatar;
            if ($avatar) {
                if (substr($avatar, 0, 4) == 'http') {
                    $path = $this->getExtFile($avatar);
                    if ($path) {
                        $file = (new SaveFileForObjectClass($user))
                            ->name('avatar.jpg')
                            ->size(filesize($path))
                            ->type(mime_content_type($path))
                            ->file($path)
                            ->description('avatar')
                            ->run();
                    }
                }
            }
        }
    }

    private function getExtFile($url)
    {
        $filename = 'avatar.jpg';
        $tempImage = tempnam(sys_get_temp_dir(), $filename);
        try {
            copy($url, $tempImage);
            return $tempImage;
        }catch (\Exception $e) {
            return false;
        }
//        $contents = Http::get($url)->body();
//        Storage::disk("local")->put($path, $contents);
    }
}
