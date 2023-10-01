<?php

declare(strict_types=1);

namespace App\Notifications\Banner;

use App\Models\Banner\Banner;
use App\Notifications\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class ModerationPassedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    private $banner;

    public function __construct(Banner $banner)
    {
        $this->banner = $banner;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', SmsChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Модерация пройдена')
            ->greeting('Привет!')
            ->line('Ваш баннер успешно прошел модерацию')
            ->action('Перейти на баннер', route('banner.get', $this->banner))
            ->line('Спасибо что используете наше приложение!');
    }

    /**
     * undocumented function.
     */
    public function toSms(): string
    {
        return 'Ваш баннер успешно прошел модерацию.';
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
        ];
    }
}
