<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

final class CarbonServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(Carbon::class, static fn (Application $app) => new Carbon('now', 'Europe/Moscow'));
    }
}
