<?php

return [
    'path' => env('FILAMENT_PATH', 'admin'),
    'middleware' => [
        'auth' => [
            'web',
        ],
    ],
    'auth' => [
        'guard' => 'web',
    ],
];
