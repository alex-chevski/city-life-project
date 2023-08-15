<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Sms\SmsSender;
use Illuminate\Foundation\Application;
use App\Services\Sms\SmsRu;
use App\Services\Sms\ArraySender;
use App\Services\Sms\EmailSms;
use Illuminate\Support\Facades\App;
use Illuminate\Contracts\Mail\Mailer as MailerInterface;

final class SmsServiceProvider extends ServiceProvider
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
        $this->app->singleton(SmsSender::class, function(Application $app){

            $config = $app->make('config')->get('sms');

            switch ($config['driver']){
                // real sms service working
            case 'sms.ru':
                $params = $config['drivers']['sms.ru'];
                if (!empty($params['url'])){
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
                throw new \InvalidArgumentException('Undefined SMS driver ' . $config['driver']);
            }
        });

    }

}
