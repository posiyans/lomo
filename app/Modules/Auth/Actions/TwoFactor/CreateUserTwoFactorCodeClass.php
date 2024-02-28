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
        $this->user->setField('two_factor_code', $code);
        (new TelegramDeleteLoginCodeMessage($this->user))->run();
        return $code;
    }
}
