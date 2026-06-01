<?php
declare(strict_types=1);

class AlumnoValidator
{
    public static function validate(array $data): array
    {
        $errors = [];

        self::required($errors, $data, 'nombre', 'El nombre es obligatorio.');
        self::required($errors, $data, 'matricula', 'La matricula es obligatoria.');
        self::required($errors, $data, 'grupo', 'El grupo es obligatorio.');
        self::required($errors, $data, 'grado', 'El grado es obligatorio.');

        if (!empty($data['correo']) && !filter_var((string) $data['correo'], FILTER_VALIDATE_EMAIL)) {
            $errors['correo'][] = 'El correo electronico no es valido.';
        }

        if (!empty($data['telefono'])) {
            $telefono = preg_replace('/[^0-9+]/', '', (string) $data['telefono']) ?? '';
            if (strlen($telefono) < 7) {
                $errors['telefono'][] = 'El telefono no es valido.';
            }
        }

        return $errors;
    }

    private static function required(array &$errors, array $data, string $field, string $message): void
    {
        if (!isset($data[$field]) || trim((string) $data[$field]) === '') {
            $errors[$field][] = $message;
        }
    }
}
