<?php

namespace App\Modules\Comment\Notifications;

use App\Modules\User\Models\UserModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class CensorCommentsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $user;
    private $text;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(UserModel $user, $text)
    {
        $this->user = $user;
        $this->text = $text;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['telegram'];
    }

    public function toTelegram($notifiable)
    {
        $text = '***Цензура***' . "\n";
        $user = $this->user;
        if ($user) {
            $text .= $user->last_name . ' ' . $user->name . "\n";;
        }
        $text .= $this->text;
        return TelegramMessage::create()
            // Optional recipient user id.
            ->chunk()
            ->content($text);
//                ->button('Смотреть', $url);

    }


    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
