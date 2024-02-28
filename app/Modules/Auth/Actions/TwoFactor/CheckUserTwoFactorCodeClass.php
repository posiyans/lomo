<?php

namespace App\Modules\Auth\Actions\TwoFactor;

use App\Models\User;
use App\Modules\Telegram\Classes\TelegramDeleteLoginCodeMessage;
use App\Modules\User\Models\UserModel;
use PragmaRX\Google2FA\Google2FA;

class CheckUserTwoFactorCodeClass
{
    private $user;

    private $code;

    public function __construct(UserModel $user, $code)
    {
        $this->code = $code;
        $this->user = $user;
    }

    public function run()
    {
        $two_factor_code = $this->user->getField('two_factor_code', null);
        if ($two_factor_code && $two_factor_code == $this->code) {
            $this->user->setField('two_factor_code', null);
            $this->deleteTelegramCode();
            return true;
        }
        if (in_array('google2fa', $this->user->getField('two_factor_enable', []))) {
            $google2fa = new Google2FA();
            $secret = $this->user->getField('two_factor_secret', false);
            if ($secret && $google2fa->verifyKey($secret, $this->code)) {
                $this->deleteTelegramCode();
                return true;
            }
        }

        return throw new \Exception('Не верный код');
    }

    private function deleteTelegramCode()
    {
        (new TelegramDeleteLoginCodeMessage($this->user))->run();
    }

}
