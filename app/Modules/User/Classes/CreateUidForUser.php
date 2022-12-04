<?php
namespace App\Modules\User\Classes;

use App\Modules\File\Models\FileModel;
use App\Modules\File\Repositories\GetObjectForFileRepository;
use App\Modules\User\Models\UserModel;
use App\Modules\User\Models\UserUidModel;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Nonstandard\Uuid;

class CreateUidForUser
{
    private $uid;

    public function __construct(UserModel $user)
    {
        $this->uid = new UserUidModel();
        $this->uid->user_id = $user->id;
        $this->uid->uid = \Ramsey\Uuid\Uuid::uuid4();
    }

    /**
     * @return UserUidModel
     * @throws \Exception
     */
    public function run()
    {
        If ($this->uid->save()) {
            return $this->uid;
        }
        throw new \Exception('Ошибука создания');
    }

}