<?php
declare(strict_types=1);

$origins = getenv('CORS_ALLOWED_ORIGINS') ?: '*';
$allowedOrigins = array_values(array_filter(array_map('trim', explode(',', $origins))));

return [
    'allowed_origins' => $allowedOrigins ?: ['*'],
    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
    'allowed_headers' => [
        'Content-Type',
        'Authorization',
        'X-Requested-With',
        'X-API-Key',
    ],
    'exposed_headers' => [
        'Content-Type',
        'X-Request-Id',
    ],
    'allow_credentials' => false,
    'max_age' => 86400,
];
