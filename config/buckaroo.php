<?php

return [
    'website_key' => env('BPE_WEBSITE_KEY', 'XXX'),
    'secret_key' => env('BPE_SECRET_KEY', 'XXX'),
    'mode' => env('BPE_MODE', 'live'),

    'transaction_model' => Buckaroo\Laravel\Models\BuckarooTransaction::class,

    'routes' => [
        'load' => env('BPE_LOAD_ROUTES', true),
        'prefix' => env('BPE_ROUTE_PATH', 'buckaroo'),
    ],
];
