<?php

declare(strict_types=1);

namespace App\Providers;

use Idma\Robokassa\Payment;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

final class PaymentServiceProvider extends ServiceProvider
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
        $this->app->singleton(Payment::class, static function (Application $app) {
            $config = $app->make('config')->get('payment');

            switch ($config['driver']) {
                // real payment service working
                case 'robokassa':
                    $params = $config['drivers']['robokassa'];
                    return new Payment($params['app_id'], $params['password1'], $params['password2'], $params['test']);
                    // case 'yandex.pay':
                    // ..........................
                    // for tests
                case 'array':
                    return new ArraySender();
                    // for default
                default:
                    throw new InvalidArgumentException('Undefined Payment driver ' . $config['driver']);
            }
        });
    }
}
