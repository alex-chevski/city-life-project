<?php

declare(strict_types=1);

namespace App\Listeners\Banner;

use App\Notifications\Banner\ModerationPassedNotification;

class ModerationPassedListener
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $banner = $event->banner;
        $banner->user->notify(new ModerationPassedNotification($banner));
    }
}
