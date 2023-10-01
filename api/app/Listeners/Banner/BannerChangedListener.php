<?php

declare(strict_types=1);

namespace App\Listeners\Banner;

use App\Jobs\Banner\ReindexBanner;

class BannerChangedListener
{
    public function handle($event): void
    {
        ReindexBanner::dispatch($event->banner);
    }
}
