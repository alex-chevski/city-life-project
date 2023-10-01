<?php

declare(strict_types=1);

namespace App\Notifications\Advert;

use App\Models\Adverts\Advert\Advert;
use App\Notifications\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class ModerationRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    private $advert;

    public function __construct(Advert $advert)
    {
        $this->advert = $advert;
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
            ->subject('Объявление не прошло проверку')
            ->greeting('Привет!')
            ->line('Причина: ' . $this->advert->reject_reason)
            ->action('Перейти на объявление', route('adverts.show', $this->advert))
            ->line('Спасибо что используете наше приложение!');
    }

    /**
     * undocumented function.
     */
    public function toSms(): string
    {
        return 'Ваше объявление не прошло модерацию причина: ' . $this->advert->reject_reason;
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
