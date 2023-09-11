<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Banner\CostCalculator;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CostCalculator::class, static function (Application $app) {
            $config = $app->make('config')->get('banner');
            return new CostCalculator((int)$config['price']);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../vendor/laravel/framework/src/Illuminate/Mail/resources/views/html/header.blade.php' => resource_path('views/html/layout.blade.php'),
            __DIR__ . '/../../vendor/laravel/framework/src/Illuminate/Mail/resources/views/text/layout.blade.php' => resource_path('views/text/layout.blade.php'),
        ], 'mail_view_override');
    }
}
