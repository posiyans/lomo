<?php

namespace App\Modules\Auth\Actions\TwoFactor;

use App\Modules\Telegram\Classes\TelegramDeleteLoginCodeMessage;
use App\Modules\User\Models\UserModel;

class CreateUserTwoFactorCodeClass
{
    private $user;


    public function __construct(UserModel $user)
    {
        $this->user = $user;
    }

    public function run()
    {
        $code = rand(100000, 999999);
        $opt = $this->user->options;
        $opt['two_code'] = $code;
        $this->user->options = $opt;
        $this->user->save();
        (new TelegramDeleteLoginCodeMessage($this->user))->run();
        return $code;
    }
}
