<?php

declare(strict_types=1);

namespace App\Listeners\Advert;

use Illuminate\Support\Facades\Storage;

class PhotoRemovedListener
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        if (!$this->hasFiles($event->files)) {
            return;
        }
        foreach ($event->files as $photo) {
            Storage::delete($photo->file);
        }
    }

    private function hasFiles($files)
    {
        return $files ? true : false;
    }
}
