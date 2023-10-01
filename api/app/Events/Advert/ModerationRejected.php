<?php

declare(strict_types=1);

namespace App\Events\Advert;

use App\Models\Adverts\Advert\Advert;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModerationRejected
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public Advert $advert)
    {
        $this->advert = $advert;
    }
}
