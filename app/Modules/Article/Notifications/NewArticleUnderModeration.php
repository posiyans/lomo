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
        $data = ['mail'];
        if ($notifiable->getField('telegram', false)) {
            $data[] = 'telegram';
        }
        return $data;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $author = '';
        $user = $this->article->user;
        if ($user) {
            $author .= $this->article->user->last_name . ' ' . $this->article->user->name;
        }
        $title = $this->article->title;
        $url = env('APP_URL') . '/admin/article/edit/' . $this->article->id;
        $text = $this->article->text;
        return (new MailMessage)
            ->subject('Новая статья')
            ->greeting("Новая статья на модерацию")
            ->salutation('С Уважением, Администрация сайта')
            ->action('Смотреть', $url)
            ->line('На сайте добавлена новая статься и она ожидает модерации')
            ->line($author)
            ->line($title)
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
        if (env('APP_ENV') == 'local') {
            $url = 'http://127.0.0.1:8099/' . '/admin/article/edit/' . $this->article->id;
        } else {
            $url = env('APP_URL') . '/admin/article/edit/' . $this->article->id;
        }
        if (isset($notifiable->options['telegram']) && !empty($notifiable->options['telegram'])) {
            return TelegramMessage::create()
                // Optional recipient user id.
                ->chunk()
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
