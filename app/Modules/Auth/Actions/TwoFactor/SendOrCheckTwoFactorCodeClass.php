<?php


namespace App\Modules\Auth\Actions\TwoFactor;

use App\Modules\User\Models\UserModel;
use App\Notifications\TwoFactorAuthentication;

class SendOrCheckTwoFactorCodeClass
{
    private $user;
    private $code;


    public function __construct(UserModel $user)
    {
        $this->user = $user;
    }

    public function code($code = false)
    {
        $this->code = $code;
        return $this;
    }

    public function run()
    {
        if ($this->user->options['two_factor'] &&
            (
                (in_array('google2fa', $this->user->options['two_factor_enable']) && $this->user->options['twofa_secret']) ||
                (in_array('telegram', $this->user->options['two_factor_enable']) && isset($this->user->options['telegram']) && !empty($this->user->options['telegram']))
            )
        ) {
            if ($this->code) {
                return $this->checkCode();
            } else {
                return $this->sendCode();
            }
        }

        throw new \Exception('2fa disable');
    }

    private function checkCode()
    {
        try {
            (new CheckUserTwoFactorCodeClass($this->user, $this->code))->run();
        } catch (\Exception $e) {
            return response(['status' => 'errorCode', 'error' => $e->getMessage()], 200);
        }
        throw new \Exception('2fa ok');
    }

    private function sendCode()
    {
        $code = (new CreateUserTwoFactorCodeClass($this->user))->run();
        try {
            \Notification::sendNow($this->user, new TwoFactorAuthentication($code));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
            throw new \Exception('send code error');
        }
        return response(['status' => 'sendCode'], 200);
    }
}
