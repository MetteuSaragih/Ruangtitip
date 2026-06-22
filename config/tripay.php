<?php

return [
    'merchant_code' => env('TRIPAY_MERCHANT_CODE'),
    'api_key' => env('TRIPAY_API_KEY'),
    'private_key' => env('TRIPAY_PRIVATE_KEY'),
    'is_production' => env('TRIPAY_IS_PRODUCTION', false),
    'base_url' => env('TRIPAY_IS_PRODUCTION', false)
        ? 'https://tripay.co.id/api'
        : 'https://tripay.co.id/api-sandbox',
];
