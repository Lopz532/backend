<?php
declare(strict_types=1);

class AuthValidator
{
    public static function validateLogin(array $data): array
    {
        $errors = [];

        if (empty($data['usuario'])) {
            $errors['usuario'][] = 'El usuario es obligatorio.';
        } elseif (strlen((string) $data['usuario']) < 3) {
            $errors['usuario'][] = 'El usuario debe tener al menos 3 caracteres.';
        }

        if (empty($data['password'])) {
            $errors['password'][] = 'La contrasena es obligatoria.';
        } elseif (strlen((string) $data['password']) < 4) {
            $errors['password'][] = 'La contrasena debe tener al menos 4 caracteres.';
        }

        return $errors;
    }
}
