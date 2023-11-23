<?php

namespace App\Modules\Social\vendor\Vk\Actions;

use App\Modules\Social\Models\LinkedSocialAccounts;
use App\Modules\User\Models\UserModel;

class ChangeUserForSocialAccount
{

    private $soc_user;


    public function __construct(LinkedSocialAccounts $soc_user, UserModel $user)
    {
        $this->soc_user = $soc_user;
        $this->soc_user->user_id = $user->id;
    }

    public function run()
    {
        $this->soc_user->logAndSave('Смена главного порльзователя');
        return $this->soc_user;
    }

}