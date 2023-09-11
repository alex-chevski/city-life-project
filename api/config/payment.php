<?php

declare(strict_types=1);

return [
    'driver' => env('PAYMENT_DRIVER', 'without_service'),

    'drivers' => [
        // real service to send own data
        'robokassa' => [
            'app_id' => env('ROBOKASSA_NAME'),
            'password1' => env('ROBOKASSA_PASSWORD1'),
            'password2' => env('ROBOKASSA_PASSWORD2'),
            'test' => env('ROBOKASSA_TEST'),
        ],
        // 'yandex_pay' => [

        // ],
    ],
];
