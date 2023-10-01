<?php

declare(strict_types=1);

namespace App\Jobs\Advert;

use App\Models\Adverts\Advert\Advert;
use App\Services\Search\AdvertIndexer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReindexAdvert implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private Advert $advert)
    {
        $this->advert = $advert;
    }

    /**
     * Execute the job.
     */
    public function handle(AdvertIndexer $indexer): void
    {
        $indexer->index($this->advert);
    }
}
