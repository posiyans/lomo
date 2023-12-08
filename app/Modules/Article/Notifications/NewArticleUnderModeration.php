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
        return ['telegram'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $text = 'Новая статья на модерацию ';
//        $text .= $this->article->user_id;
//        $text .= json_encode($this->article->user->attributesToArray());
//        $text .= $this->article->user->fullName();
//        $text .= $this->article->title;
        return (new MailMessage)
            ->subject('Новая статья')
            ->salutation('С Уважением, Администрация сайта')
            ->line('На сайте добавлена новая статься и она ожидает модерации')
//            ->action('Сбросить пароль', config('app.url') . '/auth/change-password?token=' . $this->token . '&email=' . $notifiable->getEmailForPasswordReset())
//            ->line('Время действия ссылки для сброса пароля истечет через 60 минут.')
            ->line($text);
    }


    public function toTelegram($notifiable)
    {
        $text = 'Новая статья на модерацию ';
        $text .= $this->article->title;
        $url = 'https://localhost/admin/article/edit/1';
        return TelegramMessage::create()
            // Optional recipient user id.
            ->to(env('TELEGRAM_BOT_CHAT_ID'))
            // Markdown supported.
            ->content($text)
//            ->content($this->article->title)
//            ->content($this->article->text)

            // (Optional) Blade template for the content.
            // ->view('notification', ['url' => $url])

            // (Optional) Inline Buttons
            ->button('Смотреть', 'https://localhost/admin/article/edit/1');
//            ->button('View Invoice', 'https://ya.ru/');
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
