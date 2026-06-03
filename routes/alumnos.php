<?php
declare(strict_types=1);

return [
    [
        'methods' => ['POST'],
        'path' => '/alumnos/registrar',
        'auth' => true,
        'handler' => [AlumnoController::class, 'registrar'],
    ],
    [
        'methods' => ['POST'],
        'path' => '/fichas/registrar',
        'auth' => false,
        'handler' => [FichaController::class, 'registrar'],
    ],
    [
        'methods' => ['POST'],
        'path' => '/inscripciones/registrar',
        'auth' => false,
        'handler' => [InscripcionController::class, 'registrar'],
    ],
];
