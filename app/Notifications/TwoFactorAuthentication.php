<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use NotificationChannels\Telegram\TelegramMessage;

class TwoFactorAuthentication extends Notification
{
    use Queueable;


    private $code;

    /**
     * Create a new notification instance.
     */
    public function __construct($code)
    {
        $this->code = $code;
    }


    /**
     * Определите, нужно ли отправлять уведомление.
     *
     * @param mixed $notifiable
     * @param string $channel
     * @return bool
     */
    public function shouldSend($notifiable, $channel)
    {
        return true;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $route = [];
        if ($notifiable->email && in_array('mail', $notifiable->getField('two_factor_enable', []))) {
            $route[] = 'mail';
        }
        if ($notifiable->getField('telegram', false) && in_array('telegram', $notifiable->getField('two_factor_enable', []))
        ) {
            $route[] = 'telegram';
        }
        return $route;
    }


    /**
     * Get the mail representation of the notification.
     */
    public function toTelegram(object $notifiable)
    {
        $content = "Код для входа:" . $this->code;
        return (new TelegramMessage())
            ->to($notifiable->getField('telegram', false))
            ->content($content);
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        Log::error("mail");
        Log::error($this->code);
        return (new MailMessage)
            ->subject('Код для входа ' . $this->code)
            ->greeting('Код для входа')
            ->action($this->code, '#')
            ->line('Для входа в систему введите данный код!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
