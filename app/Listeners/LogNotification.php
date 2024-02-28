<?php

namespace App\Listeners;

class LogNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        if (get_class($event->notification) == 'App\Notifications\TwoFactorAuthentication' &&
            $event->channel == 'telegram'
        ) {
            $opt = $event->notifiable->setField('telegram_login_message_id', $event->response['result']['message_id']);
        }
    }
}
