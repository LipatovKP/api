<?php

return [
    'yandex_business' => [
        'base_url' => env('YANDEX_BUSINESS_API_BASE_URL', 'https://api.business.yandex.ru'),
        'token' => env('YANDEX_BUSINESS_OAUTH_TOKEN'),
        'per_page' => env('YANDEX_BUSINESS_PER_PAGE', 20),
    ],
];
