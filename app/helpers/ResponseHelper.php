<?php
declare(strict_types=1);

class ResponseHelper
{
    public static function json(array $payload, int $statusCode = 200, array $headers = []): void
    {
        if (!headers_sent()) {
            http_response_code($statusCode);
            header('Content-Type: application/json; charset=utf-8');

            foreach ($headers as $name => $value) {
                header($name . ': ' . $value);
            }
        }

        echo json_encode(
            $payload,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE
        );
        exit;
    }

    public static function success(string $message, array $data = [], int $statusCode = 200, array $meta = []): void
    {
        self::json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => $meta,
        ], $statusCode);
    }

    public static function error(string $message, int $statusCode = 400, array $errors = [], array $meta = []): void
    {
        self::json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'meta' => $meta,
        ], $statusCode);
    }

    public static function validation(array $errors, string $message = 'Validacion fallida'): void
    {
        self::error($message, 422, $errors);
    }
}
