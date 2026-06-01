<?php
declare(strict_types=1);

$basePath = dirname(__DIR__);

loadEnvFile($basePath . '/.env');

require_once $basePath . '/app/helpers/JsonHelper.php';
require_once $basePath . '/app/helpers/ResponseHelper.php';
require_once $basePath . '/app/helpers/XmlHelper.php';
require_once $basePath . '/app/config/app.php';
require_once $basePath . '/app/config/cors.php';
require_once $basePath . '/app/config/googleSheets.php';
require_once $basePath . '/app/services/ResponseService.php';
require_once $basePath . '/app/services/SecurityService.php';
require_once $basePath . '/app/services/XmlService.php';
require_once $basePath . '/app/services/GoogleSheetsService.php';
require_once $basePath . '/app/validators/SecurityValidator.php';
require_once $basePath . '/app/validators/AuthValidator.php';
require_once $basePath . '/app/validators/AlumnoValidator.php';
require_once $basePath . '/app/models/Alumno.php';
require_once $basePath . '/app/models/Beca.php';
require_once $basePath . '/app/models/Imss.php';
require_once $basePath . '/app/models/Usuario.php';
require_once $basePath . '/app/controllers/AuthCotroller.php';
require_once $basePath . '/app/controllers/AlumnoController.php';
require_once $basePath . '/app/controllers/BecaController.php';
require_once $basePath . '/app/controllers/ImssController.php';
require_once $basePath . '/app/middleware/CorsMiddleware.php';
require_once $basePath . '/app/middleware/ErrorMiddleware.php';
require_once $basePath . '/app/middleware/AuthMiddleware.php';

$appConfig = require $basePath . '/app/config/app.php';
$corsConfig = require $basePath . '/app/config/cors.php';

date_default_timezone_set((string) $appConfig['timezone']);

ErrorMiddleware::register((bool) $appConfig['debug']);
CorsMiddleware::handle($corsConfig);

$requestMethod = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
$requestPath = normalizePath($_SERVER['REQUEST_URI'] ?? '/');
$requestData = JsonHelper::decodeInput();
$requestId = SecurityService::generateRequestId();

$routes = array_merge(
    require $basePath . '/routes/web.php',
    require $basePath . '/routes/auth.php',
    require $basePath . '/routes/alumnos.php',
    require $basePath . '/routes/becas.php',
    require $basePath . '/routes/imss.php'
);

dispatch($routes, $requestMethod, $requestPath, $requestData, $requestId);

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

function normalizePath(string $uri): string
{
    $path = parse_url($uri, PHP_URL_PATH) ?: '/';
    $path = str_replace('\\', '/', $path);
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');

    if ($scriptName !== '') {
        $scriptDir = dirname($scriptName);
        if ($scriptDir !== '/' && str_starts_with($path, $scriptDir)) {
            $path = substr($path, strlen($scriptDir));
        }
    }

    foreach (['/index.php', '/api.php'] as $prefix) {
        if (str_starts_with($path, $prefix)) {
            $path = substr($path, strlen($prefix));
        }
    }

    if ($path === '') {
        $path = '/';
    }

    if (str_starts_with($path, '/api/')) {
        $path = substr($path, 4);
    } elseif ($path === '/api') {
        $path = '/';
    }

    return '/' . ltrim($path, '/');
}

function dispatch(array $routes, string $method, string $path, array $requestData, string $requestId): void
{
    foreach ($routes as $route) {
        $allowedMethods = array_map('strtoupper', $route['methods'] ?? []);
        $routePath = rtrim((string) ($route['path'] ?? '/'), '/') ?: '/';
        $currentPath = rtrim($path, '/') ?: '/';

        if (!in_array($method, $allowedMethods, true) || $routePath !== $currentPath) {
            continue;
        }

        if (($route['auth'] ?? false) === true) {
            AuthMiddleware::check();
        }

        $handler = $route['handler'] ?? null;

        if (is_array($handler) && count($handler) === 2 && class_exists($handler[0])) {
            $controller = new $handler[0]();
            $methodName = (string) $handler[1];

            if (!method_exists($controller, $methodName)) {
                ResponseService::error('Ruta no disponible.', 500, [], ['request_id' => $requestId]);
            }

            $controller->{$methodName}($requestData);
            return;
        }

        if (is_callable($handler)) {
            $handler();
            return;
        }

        ResponseService::error('Ruta invalida.', 500, [], ['request_id' => $requestId]);
    }

    ResponseService::error('Recurso no encontrado.', 404, [], ['request_id' => $requestId]);
}
