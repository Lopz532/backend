<?php
declare(strict_types=1);

class SecurityValidator
{
    public static function validateApiKey(array $data): array
    {
        $errors = [];

        if (empty($data['api_key'])) {
            $errors['api_key'][] = 'El campo api_key es obligatorio.';
        }

        return $errors;
    }

    public static function validateTokenRequest(array $data): array
    {
        $errors = [];

        if (empty($data['usuario'])) {
            $errors['usuario'][] = 'El campo usuario es obligatorio.';
        }

        if (empty($data['password'])) {
            $errors['password'][] = 'El campo password es obligatorio.';
        }

        return $errors;
    }
}
