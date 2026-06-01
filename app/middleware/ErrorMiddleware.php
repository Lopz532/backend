<?php
declare(strict_types=1);

class ErrorMiddleware
{
    public static function register(bool $debug = false): void
    {
        set_error_handler(static function (int $severity, string $message, string $file, int $line) use ($debug): bool {
            if (!(error_reporting() & $severity)) {
                return false;
            }

            throw new ErrorException($message, 0, $severity, $file, $line);
        });

        set_exception_handler(static function (Throwable $exception) use ($debug): void {
            $payload = [
                'success' => false,
                'message' => 'Error interno del servidor',
                'errors' => [],
            ];

            if ($debug) {
                $payload['message'] = $exception->getMessage();
                $payload['errors'] = [
                    'type' => get_class($exception),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ];
            }

            error_log($exception->__toString());

            ResponseHelper::json($payload, 500);
        });
    }
}
