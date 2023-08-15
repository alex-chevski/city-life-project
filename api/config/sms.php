<?php

return [
    'driver' => env('SMS_DRIVER', 'email'),

    'drivers' => [
        //real service to send own data
        'sms.ru' => [
            'app_id' => env('SMS_SMS_RU_APP_ID'),
            'url' => env('SMS_SMS_RU_URL'),
        ],
    ],
];
