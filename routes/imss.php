<?php
declare(strict_types=1);

return [
    [
        'methods' => ['POST'],
        'path' => '/imss/registrar',
        'auth' => true,
        'handler' => [ImssController::class, 'registrar'],
    ],
];
