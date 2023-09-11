<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Sms\ArraySender;
use App\Services\Sms\EmailSms;
use App\Services\Sms\SmsRu;
use App\Services\Sms\SmsSender;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

final class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->singleton(SmsSender::class, static function (Application $app) {
            $config = $app->make('config')->get('sms');

            switch ($config['driver']) {
                // real sms service working
                case 'sms.ru':
                    $params = $config['drivers']['sms.ru'];
                    if (!empty($params['url'])) {
                        return new SmsRu($params['app_id'], $params['url']);
                    }
                    return new SmsRu($params['app_id']);
                    // for tests
                case 'array':
                    return new ArraySender();
                    // for default
                case 'email':
                    return new EmailSms($app->make('Illuminate\Contracts\Mail\Mailer'), $app->make('App\Models\User\User'));
                default:
                    throw new InvalidArgumentException('Undefined SMS driver ' . $config['driver']);
            }
        });
    }
}
