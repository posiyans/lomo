<?php

namespace App\Modules\BanUser\Actions;

use App\Modules\BanUser\Repositories\BanUserRepository;
use App\Modules\BanUser\Repositories\BanUserTypeRepository;
use App\Modules\User\Models\UserModel;

/**
 * Проверить возможность оставлять комментарии
 *
 */
class CheckUserBanAction
{

    private $user;
    private $type;
    private $uid;

    public function __construct(UserModel $user)
    {
        $this->user = $user;
    }

    public function type($type)
    {
        $this->type = $type;
        return $this;
    }

    public function typeUid($uid)
    {
        $this->uid = $uid;
        return $this;
    }

    public function run()
    {
        if (!$this->user->email_verified_at) {
            throw new \Exception('Чтобы добавлять комментарии необходимо подтвердить свою почту');
        }
        try {
            (new BanUserRepository())
                ->forUser($this->user)
                ->forClass(BanUserTypeRepository::getTypeClass('all'))
                ->noBan();
            (new BanUserRepository())
                ->forUser($this->user)
                ->forClass(BanUserTypeRepository::getTypeClass($this->type))
                ->forClassUid('all')
                ->noBan();
            (new BanUserRepository())
                ->forUser($this->user)
                ->forClassUid($this->uid)
                ->noBan();
            return true;
        } catch (\Exception $e) {
            throw new \Exception('Комментарии отключены');
        }
    }


}
