<?php

namespace App\Modules\Article\Notifications;

use App\Modules\Article\Models\ArticleModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class NewArticleUnderModeration extends Notification implements ShouldQueue
{
    use Queueable;

    public $article;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ArticleModel $article)
    {
        $this->article = $article;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'telegram'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $text = '';
        $user = $this->article->user;
        if ($user) {
            $text .= $this->article->user->last_name . ' ' . $this->article->user->name . '<br>';
        }
        $text .= $this->article->title . "\n";
        $url = env('APP_URL') . '/admin/article/edit/' . $this->article->id;
        return (new MailMessage)
            ->subject('Новая статья')
            ->greeting("Новая статья на модерацию")
            ->salutation('С Уважением, Администрация сайта')
            ->action('Смотреть', $url)
            ->line('На сайте добавлена новая статься и она ожидает модерации')
            ->line($text);
    }


    public function toTelegram($notifiable)
    {
        $text = '***Новая статья на модерацию***' . "\n";
        $text .= $this->article->title . "\n";
        $user = $this->article->user;
        if ($user) {
            $text .= $this->article->user->last_name . ' ' . $this->article->user->name;
        }
        $url = env('APP_URL') . '/admin/article/edit/' . $this->article->id;
        if (isset($notifiable->options['telegram']) && !empty($notifiable->options['telegram'])) {
            return TelegramMessage::create()
                // Optional recipient user id.
//                ->to($notifiable->options['telegram'])
                ->to($notifiable)
                ->content($text)
                ->button('Смотреть', $url);
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
