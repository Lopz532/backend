<?php
declare(strict_types=1);

class CorsMiddleware
{
    public static function handle(array $corsConfig): void
    {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
        $allowedOrigins = $corsConfig['allowed_origins'] ?? ['*'];

        if (in_array('*', $allowedOrigins, true)) {
            header('Access-Control-Allow-Origin: *');
        } elseif (in_array($origin, $allowedOrigins, true)) {
            header('Access-Control-Allow-Origin: ' . $origin);
            header('Vary: Origin');
        }

        header('Access-Control-Allow-Methods: ' . implode(', ', $corsConfig['allowed_methods'] ?? ['GET', 'POST', 'OPTIONS']));
        header('Access-Control-Allow-Headers: ' . implode(', ', $corsConfig['allowed_headers'] ?? ['Content-Type']));
        header('Access-Control-Expose-Headers: ' . implode(', ', $corsConfig['exposed_headers'] ?? []));
        header('Access-Control-Max-Age: ' . (string) ($corsConfig['max_age'] ?? 86400));

        if (!empty($corsConfig['allow_credentials'])) {
            header('Access-Control-Allow-Credentials: true');
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
            ResponseHelper::json([
                'success' => true,
                'message' => 'Preflight OK',
            ], 200);
        }
    }
}
