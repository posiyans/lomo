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
//        $url = env('APP_URL') . '/admin/article/edit/' . $this->article->id;
        if (isset($notifiable->options['telegram']) && !empty($notifiable->options['telegram'])) {
            $text = '***Новый комментарий***' . "\n";
            $commentedModel = CommentTypeRepository::getCommentedRoleObject($this->comment->parentModel);
            $user = $this->comment->user;
            if ($user) {
                $text .= $user->last_name . ' ' . $user->name . "\n";;
            }
            $text .= 'для ' . $commentedModel->descriptionForComment()['label'] . "\n";
            $text .= $this->comment->message . "\n";
//            $url = env('APP_URL') . $commentedModel->descriptionForComment()['url'];
            return TelegramMessage::create()
                // Optional recipient user id.
                ->to($notifiable->options['telegram'])
                ->content($text);
//                ->button('Смотреть', $url);
        } else {
            throw new \Exception('Нt указан телеграм id');
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
