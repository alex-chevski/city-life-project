<?php

declare(strict_types=1);

namespace App\Listeners\Advert;

use App\Notifications\Advert\ModerationPassedNotification;
use App\Services\Search\AdvertIndexer;

class ModerationPassedListener
{
    private $indexer;

    public function __construct(AdvertIndexer $indexer)
    {
        $this->indexer = $indexer;
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $advert = $event->advert;
        $advert->user->notify(new ModerationPassedNotification($advert));
    }
}
