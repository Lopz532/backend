<?php
declare(strict_types=1);

$basePath = dirname(__DIR__, 2);

loadEnvFile($basePath . '/.env');

require_once $basePath . '/app/helpers/JsonHelper.php';
require_once $basePath . '/app/helpers/ResponseHelper.php';
require_once $basePath . '/app/helpers/FormHelper.php';
require_once $basePath . '/app/helpers/XmlHelper.php';
require_once $basePath . '/app/config/app.php';
require_once $basePath . '/app/config/cors.php';
require_once $basePath . '/app/config/googleSheets.php';
require_once $basePath . '/app/config/schoolForms.php';
require_once $basePath . '/app/services/ResponseService.php';
require_once $basePath . '/app/services/SecurityService.php';
require_once $basePath . '/app/services/XmlService.php';
require_once $basePath . '/app/services/GoogleSheetsService.php';
require_once $basePath . '/app/validators/FichaValidator.php';
require_once $basePath . '/app/validators/InscripcionValidator.php';
require_once $basePath . '/app/models/Ficha.php';
require_once $basePath . '/app/models/Inscripcion.php';
require_once $basePath . '/app/controllers/FichaController.php';
require_once $basePath . '/app/controllers/InscripcionController.php';
require_once $basePath . '/app/middleware/CorsMiddleware.php';
require_once $basePath . '/app/middleware/ErrorMiddleware.php';

$appConfig = require $basePath . '/app/config/app.php';
$corsConfig = require $basePath . '/app/config/cors.php';

date_default_timezone_set((string) $appConfig['timezone']);

ErrorMiddleware::register((bool) $appConfig['debug']);
CorsMiddleware::handle($corsConfig);

function loadEnvFile(string $path): void
{
    if (!is_file($path) || !is_readable($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if ($lines === false) {
        return;
    }

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = array_map('trim', explode('=', $line, 2));
        $value = trim($value, "\"'");

        if ($key !== '' && getenv($key) === false) {
            putenv($key . '=' . $value);
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

function schoolApiRequestData(): array
{
    return JsonHelper::decodeInput();
}

function schoolApiRequirePost(): void
{
    $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

    if ($method !== 'POST') {
        ResponseService::error('Metodo no permitido.', 405);
    }
}

function schoolApiDispatchFicha(array $requestData): void
{
    (new FichaController())->registrar($requestData);
}

function schoolApiDispatchInscripcion(array $requestData): void
{
    (new InscripcionController())->registrar($requestData);
}
