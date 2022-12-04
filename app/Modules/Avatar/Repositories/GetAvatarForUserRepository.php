<?php
namespace App\Modules\Avatar\Repositories;

use App\Modules\File\Classes\SaveFileForObjectClass;
use App\Modules\File\Repositories\GetFilesForObjectRepository;
use App\Modules\User\Models\UserModel;
use Illuminate\Support\Facades\Http;

class GetAvatarForUserRepository {

    private $user;

    public function __construct(UserModel $user)
    {
        $this->user = $user;
    }

    public function run()
    {
        return (new GetFilesForObjectRepository($this->user))->description('avatar')->first();

    }


}