<?php
declare(strict_types=1);

return [
    [
        'methods' => ['POST'],
        'path' => '/becas/registrar',
        'auth' => true,
        'handler' => [BecaController::class, 'registrar'],
    ],
];
