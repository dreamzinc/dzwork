<?php

return [
    // Application configuration
    'name' => 'DzWork Framework',
    'debug' => true,
    'url' => 'http://localhost',
    'timezone' => 'UTC',
    
    // Service providers that should be registered
    'providers' => [
        // Add your service providers here
    ],
    
    // Class aliases
    'aliases' => [
        'App' => DzWork\Core\App::class,
        'Router' => DzWork\Core\Router::class,
        'Request' => DzWork\Core\Request::class,
        'Response' => DzWork\Core\Response::class,
    ],
    
    // Middleware groups
    'middleware' => [
        'web' => [
            // Add your web middleware here
        ],
        'api' => [
            // Add your API middleware here
        ]
    ],
];
