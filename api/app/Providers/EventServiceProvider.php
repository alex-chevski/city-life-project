<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\Advert\AdvertClosed;
use App\Events\Advert\ModerationPassed as AdvertPassed;
use App\Events\Advert\ModerationRejected as AdvertRejected;
use App\Events\Banner\BannerClosed;
use App\Events\Banner\ModerationPassed as BannerPassed;
use App\Listeners\Advert\AdvertChangedListener;
use App\Listeners\Advert\ModerationPassedListener as AdvertListener;
use App\Listeners\Advert\ModerationRejectedListener as AdvertRejectedListener;
use App\Listeners\Advert\PhotoRemovedListener as AdvertPhotoRemovedListener;
use App\Listeners\Banner\BannerChangedListener;
use App\Listeners\Banner\ModerationPassedListener as BannerListener;
use App\Listeners\Banner\PhotoRemovedListener as BannerPhotoRemovedListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

final class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        AdvertPassed::class => [
            AdvertChangedListener::class,
            AdvertListener::class,
        ],

        BannerPassed::class => [
            BannerChangedListener::class,
            BannerListener::class,
        ],

        AdvertRejected::class => [
            AdvertRejectedListener::class,
        ],

        AdvertClosed::class => [
            AdvertPhotoRemovedListener::class,
            AdvertChangedListener::class,
        ],

        BannerClosed::class => [
            BannerChangedListener::class,
            BannerPhotoRemovedListener::class,
        ],

        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            // ... other providers
            \SocialiteProviders\VKontakte\VKontakteExtendSocialite::class . '@handle',
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void {}

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
