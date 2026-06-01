<?php
declare(strict_types=1);

$appConfig = require __DIR__ . '/../app/config/app.php';

return [
    [
        'methods' => ['GET'],
        'path' => '/',
        'auth' => false,
        'handler' => static function () use ($appConfig): void {
            ResponseService::success('API escolar activa.', [
                'name' => $appConfig['name'],
                'status' => 'ok',
            ]);
        },
    ],
    [
        'methods' => ['GET'],
        'path' => '/health',
        'auth' => false,
        'handler' => static function (): void {
            ResponseService::success('Servicio saludable.', [
                'time' => gmdate('c'),
                'php_version' => PHP_VERSION,
            ]);
        },
    ],
];
