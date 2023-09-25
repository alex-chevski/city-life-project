<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Adverts\Advert\Advert;
use App\Models\Banner\Banner;
use App\Models\User\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

final class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        $this->registerPermissions();
    }

    private function registerPermissions(): void
    {
        // pages
        Gate::define('manage-pages', static fn (User $user) => $user->isAdmin());

        // adverts
        Gate::define('moderate-adverts', static fn (User $user, Advert $advert) => $user->isAdmin() || $user->isModerator());
        Gate::define('show-advert', static fn (User $user, Advert $advert) => $user->isAdmin() || $user->isModerator() || $advert->user_id === $user->id);
        Gate::define('edit-own-advert', static fn (User $user, Advert $advert) => $advert->user_id === $user->id);

        // Banners
        Gate::define('manage-own-banner', static fn (User $user, Banner $banner) => $banner->user_id === $user->id);

        // Admin Panel
        Gate::define('admin-panel', static fn (User $user) => $user->isAdmin() || $user->isModerator());
        Gate::define('manage-users', static fn (User $user) => $user->isAdmin());
        Gate::define('manage-adverts-categories', static fn (User $user) => $user->isAdmin() || $user->isModerator());
        Gate::define('manage-adverts', static fn (User $user) => $user->isAdmin() || $user->isModerator());
        Gate::define('manage-regions', static fn (User $user) => $user->isAdmin());
        Gate::define('manage-banners', static fn (User $user) => $user->isAdmin() || $user->isModerator());
        Gate::define('manage-tickets', static fn (User $user) => $user->isAdmin() || $user->isModerator());
    }
}
