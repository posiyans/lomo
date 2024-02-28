<?php

namespace App\Modules\Comment\Notifications;

use App\Modules\Comment\Models\CommentModel;
use App\Modules\Comment\Repositories\CommentTypeRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class AdminNewCommentsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $comment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(CommentModel $comment)
    {
        $this->comment = $comment;
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
        if ($notifiable->getField('telegram', false)) {
            $text = '***Новый комментарий***' . "\n";
            $commentedModel = CommentTypeRepository::getCommentedRoleObject($this->comment->parentModel);
            $user = $this->comment->user;
            if ($user) {
                $text .= $user->last_name . ' ' . $user->name . "\n";;
            }
            $text .= 'для ' . $commentedModel->descriptionForComment()['label'] . "\n";
            $text .= str_replace('*', ' ', $this->comment->message) . "\n";
            if (env('APP_ENV') == 'local') {
                $url = 'http://127.0.0.1:8099/' . $commentedModel->descriptionForComment()['url'];
            } else {
                $url = env('APP_URL') . $commentedModel->descriptionForComment()['url'];
            }
            return TelegramMessage::create()
                ->chunk()
                ->content($text)
                ->button('Смотреть', $url);
        } else {
            throw new \Exception('Не указан телеграм id');
        }
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
