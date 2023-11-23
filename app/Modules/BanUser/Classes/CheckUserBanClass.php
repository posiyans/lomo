<?php

namespace App\Modules\BanUser\Classes;

use App\Modules\BanUser\Models\BanUserModel;
use App\Modules\User\Models\UserModel;

/**
 * Проверить бан пользователя
 */
class CheckUserBanClass
{
    private $query;

    public function __construct(UserModel $user)
    {
        $this->query = BanUserModel::where('user_id', $user->id);
    }

    /**
     * бан по кокретному типу обьектов комментриев (например статьтьи)
     * (не реализованно)
     * @param $type
     * @return $this
     */
    public function object($type)
    {
        return $this;
    }

    /**
     * Бан по опрделенному объекту (кокретная статью)
     * (не реализованно)
     * @param $uid
     * @return $this
     */
    public function object_uid($uid)
    {
        return $this;
    }

    public function run()
    {
        $ban = $this->query->first();
        if ($ban) {
            return $this->check_expiration_date($ban);
        }
        return false;
    }

    private function check_expiration_date(BanUserModel $ban)
    {
        if (empty($ban->end_ban_time)) {
            return [
                'ban' => true,
                'end_ban_time' => null
            ];
        } else {
            if (time() > strtotime($ban->end_ban_time)) {
                $ban->delete();
                return false;
            }
            return [
                'ban' => true,
                'end_ban_time' => $ban->end_ban_time
            ];
        }
    }


}