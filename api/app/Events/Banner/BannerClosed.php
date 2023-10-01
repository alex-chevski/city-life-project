<?php

declare(strict_types=1);

namespace App\Events\Banner;

use App\Models\Banner\Banner;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BannerClosed
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public Banner $banner, public $file)
    {
        $this->banner = $banner;
        $this->file = $file;
    }
}
