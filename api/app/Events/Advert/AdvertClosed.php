<?php

declare(strict_types=1);

namespace App\Events\Advert;

use App\Models\Adverts\Advert\Advert;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdvertClosed implements ShouldQueue
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public Advert $advert, public $files)
    {
        $this->advert = $advert;
        $this->files = $files;
    }
}
