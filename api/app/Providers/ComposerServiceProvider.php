<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\ViewComposers\MenuPagesComposer;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void {}

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('layouts.partials.pages', MenuPagesComposer::class);
    }
}
