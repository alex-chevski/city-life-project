<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Adverts\Category;
use App\Models\Region;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

final class CacheServiceProvider extends ServiceProvider
{
    private $classes = [
        Region::class,
        Category::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * @var Model $class
         */
        foreach ($this->classes as $class) {
            $this->registerFlusher($class);
        }
    }

    /**
     * undocumented function.
     */
    private function registerFlusher($class): void
    {
        $flush = static function () use ($class): void {
            Cache::tags($class)->flush();
        };

        $class::created($flush);
        $class::saved($flush);
        $class::updated($flush);
        $class::deleted($flush);
    }
}
