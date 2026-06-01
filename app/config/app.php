<?php
declare(strict_types=1);

return [
    'name' => getenv('APP_NAME') ?: 'API Escolar',
    'env' => getenv('APP_ENV') ?: 'production',
    'debug' => filter_var(getenv('APP_DEBUG') ?: 'false', FILTER_VALIDATE_BOOLEAN),
    'timezone' => getenv('APP_TIMEZONE') ?: 'America/Mexico_City',
    'url' => getenv('APP_URL') ?: '',
    'api_prefix' => getenv('API_PREFIX') ?: '/api',
    'secret' => getenv('APP_SECRET') ?: 'change-this-secret',
    'request_max_bytes' => (int) (getenv('REQUEST_MAX_BYTES') ?: 1048576),
];
