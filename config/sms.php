<?php

return [
    // "sms.ru", "array"
    'driver' => env('SMS_DRIVER', 'sms.ru'),

    'drivers' => [
        'sms.ru' => [
            'api_id' => env('SMS_SMS_RU_API_ID'),
            'url' => env('SMS_SMS_RU_API_URL'),
        ],
    ],
];
