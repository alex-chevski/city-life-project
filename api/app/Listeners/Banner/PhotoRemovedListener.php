<?php

declare(strict_types=1);

namespace App\Listeners\Banner;

use Illuminate\Support\Facades\Storage;

class PhotoRemovedListener
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        Storage::delete($event->file);
    }
}
