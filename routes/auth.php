<?php
declare(strict_types=1);

return [
    [
        'methods' => ['POST'],
        'path' => '/auth/login',
        'auth' => false,
        'handler' => [AuthController::class, 'login'],
    ],
];
