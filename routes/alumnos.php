<?php
declare(strict_types=1);

return [
    [
        'methods' => ['POST'],
        'path' => '/alumnos/registrar',
        'auth' => true,
        'handler' => [AlumnoController::class, 'registrar'],
    ],
];
