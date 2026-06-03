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
        'methods' => ['POST'],
        'path' => '/',
        'auth' => false,
        'handler' => static function () use ($requestData): void {
            $formType = strtolower(trim((string) (
                $requestData['form_type']
                ?? $requestData['tipo_formulario']
                ?? $requestData['module']
                ?? $requestData['registro']
                ?? ''
            )));

            if ($formType === 'ficha' || $formType === 'fichas') {
                (new FichaController())->registrar($requestData);
                return;
            }

            if ($formType === 'inscripcion' || $formType === 'inscripciones' || $formType === 'registro') {
                (new InscripcionController())->registrar($requestData);
                return;
            }

            $inscripcionKeys = [
                'madre_nombre',
                'madre_telefono',
                'padre_nombre',
                'padre_telefono',
                'tutor_ine',
                'tutor_curp',
            ];

            $fichaKeys = [
                'tutor_nombre',
                'tutor_lugar_procedencia',
            ];

            $hasInscripcionFields = false;

            foreach ($inscripcionKeys as $key) {
                if (!empty($requestData[$key])) {
                    $hasInscripcionFields = true;
                    break;
                }
            }

            if ($hasInscripcionFields) {
                (new InscripcionController())->registrar($requestData);
                return;
            }

            foreach ($fichaKeys as $key) {
                if (!empty($requestData[$key])) {
                    (new FichaController())->registrar($requestData);
                    return;
                }
            }

            ResponseService::error(
                'No se pudo identificar el tipo de registro. Envia form_type=ficha o form_type=inscripcion.',
                400
            );
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
