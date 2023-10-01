<?php

declare(strict_types=1);

namespace App\Jobs\Banner;

use App\Models\Banner\Banner;
use App\Services\Search\BannerIndexer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReindexBanner implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private Banner $banner)
    {
        $this->banner = $banner;
    }

    /**
     * Execute the job.
     */
    public function handle(BannerIndexer $indexer): void
    {
        $indexer->index($this->banner);
    }
}
