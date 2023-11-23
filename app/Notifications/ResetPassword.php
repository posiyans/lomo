<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting("Сброс пароля")
            ->salutation('С Уважением, Администрация сайта')
            ->subject('Сброс пароля')
            ->line('Вы получили это письмо, потому что мы получили запрос на сброс пароля для вашей учетной записи.')
            ->action('Сбросить пароль', config('app.url') . '/auth/change-password?token=' . $this->token . '&email=' . $notifiable->getEmailForPasswordReset())
            ->line('Время действия ссылки для сброса пароля истечет через 60 минут.')
            ->line('Если вы не запрашивали сброс пароля, никаких дальнейших действий не требуется.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
