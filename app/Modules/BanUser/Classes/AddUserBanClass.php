<?php

namespace App\Modules\BanUser\Classes;

use App\Modules\BanUser\Models\BanUserModel;
use App\Modules\BanUser\Repositories\BanUserTypeRepository;
use App\Modules\User\Models\UserModel;
use Illuminate\Support\Facades\Auth;

/**
 * Добавить бан пользователю
 */
class AddUserBanClass
{
    private $ban;

    public function __construct(UserModel $user)
    {
        $this->ban = new BanUserModel();
        $this->ban->user_id = $user->id;
        $this->ban->commentable_type = null;
        $this->ban->commentable_uid = null;
        $this->ban->end_ban_time = null;
        $this->ban->author_id = Auth::id() ?? null;
    }

    public function description($text)
    {
        $this->ban->description = $text;
        return $this;
    }

    public function setType($type)
    {
        $this->ban->commentable_type = BanUserTypeRepository::getTypeClass($type);
        return $this;
    }

    public function setTypeUid($uid)
    {
        $this->ban->commentable_uid = $uid;
        return $this;
    }

    public function beforeTimes($time)
    {
        $this->ban->end_ban_time = $time;
        return $this;
    }

    public function run()
    {
        if ($this->ban->logAndSave('Бан пользователя')) {
            return $this->ban;
        }
        throw new \Exception($this->ban->errors);
    }


}