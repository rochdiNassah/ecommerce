<?php declare(strict_types=1);

return [
    'websocket' => [
        'host' => env('WEBSOCKET_HOST', '127.0.0.1'),
        'port' => env('WEBSOCKET_PORT', '1112')
    ],
    'sockpull' => [
        'host' => env('SOCKPULL_HOST', '127.0.0.1'),
        'port' => env('SOCKPULL_PORT', '1111')
    ]
];
