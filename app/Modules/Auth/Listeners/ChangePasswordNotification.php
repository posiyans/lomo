<?php

namespace App\Modules\Auth\Listeners;

use App\Modules\Auth\Events\PasswordReset;
use App\Modules\Auth\Notifications\ChangePassword;

class ChangePasswordNotification
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(PasswordReset $event)
    {
        $event->user->notify(new ChangePassword());
    }
}
