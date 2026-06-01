<?php
declare(strict_types=1);

class ResponseService
{
    public static function success(string $message, array $data = [], int $statusCode = 200, array $meta = []): void
    {
        ResponseHelper::success($message, $data, $statusCode, $meta);
    }

    public static function error(string $message, int $statusCode = 400, array $errors = [], array $meta = []): void
    {
        ResponseHelper::error($message, $statusCode, $errors, $meta);
    }

    public static function validation(array $errors, string $message = 'Validacion fallida'): void
    {
        ResponseHelper::validation($errors, $message);
    }

    public static function created(string $message, array $data = [], array $meta = []): void
    {
        self::success($message, $data, 201, $meta);
    }
}
