<?php

declare(strict_types=1);

namespace App\Listeners\Advert;

use App\Notifications\Advert\ModerationRejectedNotification;

class ModerationRejectedListener
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $advert = $event->advert;
        $advert->user->notify(new ModerationRejectedNotification($advert));
    }
}
