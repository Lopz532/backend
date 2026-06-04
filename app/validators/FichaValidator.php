<?php
declare(strict_types=1);

class FichaValidator
{
    public static function validate(array $data): array
    {
        error_log('VALIDATOR:' .json_encode($data,JSON_UNESCAPED_UNICODE));
        $errors = [];
        $formsConfig = require __DIR__ . '/../config/schoolForms.php';
        $dropdowns = $formsConfig['dropdowns'] ?? [];

        foreach ([
            'nombre',
            'apellido_materno',
            'apellido_paterno',
            'curp',
            'fecha_nacimiento',
            'lugar_nacimiento',
            'lugar_procedencia',
            'edad',
            'domicilio',
            'telefono',
            'genero',
            'tipo_sangre',
            'secundaria_procedencia',
            'correo_electronico',
            'tutor_nombre',
            'tutor_domicilio',
            'tutor_telefono',
            'tutor_lugar_procedencia',
            'periodo_escolar',
            'semestre',
            'especialidad',
        ] as $field) {
            self::required($errors, $data, $field, 'El campo ' . $field . ' es obligatorio.');
        }

        self::validateEmail($errors, $data, 'correo_electronico');
        self::validatePhone($errors, $data, 'telefono');
        self::validatePhone($errors, $data, 'tutor_telefono');
        self::validateCurp($errors, $data, 'curp');
        self::validateDate($errors, $data, 'fecha_nacimiento');
        self::validateIntegerRange($errors, $data, 'edad', 3, 120);
        self::validateChoice($errors, $data, 'genero', array_keys($dropdowns['genero'] ?? []), 'Genero invalido.');
        self::validateBloodType($errors, $data, 'tipo_sangre');
        self::validateChoice($errors, $data, 'periodo_escolar', array_keys($dropdowns['periodo_escolar'] ?? []), 'Periodo escolar invalido.');
        self::validateSemester($errors, $data, 'semestre', array_keys($dropdowns['semestre'] ?? []));
        self::validateChoice($errors, $data, 'especialidad', array_keys($dropdowns['especialidad'] ?? []), 'Especialidad invalida.');

        error_log('ERRORES:'.json_encode($errors,JSON_UNESCAPED_UNICODE));
        return $errors;
    }

    private static function required(array &$errors, array $data, string $field, string $message): void
    {
        if (!isset($data[$field]) || trim((string) $data[$field]) === '') {
            $errors[$field][] = $message;
        }
    }

    private static function validateEmail(array &$errors, array $data, string $field): void
    {
        if (empty($data[$field])) {
            return;
        }

        if (!filter_var((string) $data[$field], FILTER_VALIDATE_EMAIL)) {
            $errors[$field][] = 'El correo electronico no es valido.';
        }
    }

    private static function validatePhone(array &$errors, array $data, string $field): void
    {
        if (empty($data[$field])) {
            return;
        }

        $phone = preg_replace('/[^0-9+]/', '', (string) $data[$field]) ?? '';

        if (strlen($phone) < 7) {
            $errors[$field][] = 'El telefono no es valido.';
        }
    }

    private static function validateCurp(array &$errors, array $data, string $field): void
    {
        if (empty($data[$field])) {
            return;
        }

        $curp = strtoupper(preg_replace('/\s+/', '', (string) $data[$field]) ?? '');

        if (!preg_match('/^[A-Z]{4}\d{6}[HM][A-Z]{5}[A-Z0-9]\d$/', $curp)) {
            $errors[$field][] = 'La CURP no es valida.';
        }
    }

    private static function validateDate(array &$errors, array $data, string $field): void
    {
        if (empty($data[$field])) {
            return;
        }

        if (FormHelper::parseDate($data[$field]) === null) {
            $errors[$field][] = 'La fecha no es valida.';
            return;
        }

        $normalized = FormHelper::parseDate($data[$field]);

        if ($normalized !== null && new DateTimeImmutable($normalized) > new DateTimeImmutable('today')) {
            $errors[$field][] = 'La fecha no puede ser futura.';
        }
    }

    private static function validateIntegerRange(array &$errors, array $data, string $field, int $min, int $max): void
    {
        if (empty($data[$field])) {
            return;
        }

        if (filter_var($data[$field], FILTER_VALIDATE_INT) === false) {
            $errors[$field][] = 'El campo ' . $field . ' debe ser numerico.';
            return;
        }

        $value = (int) $data[$field];

        if ($value < $min || $value > $max) {
            $errors[$field][] = 'El campo ' . $field . ' debe estar entre ' . $min . ' y ' . $max . '.';
        }
    }

    private static function validateChoice(array &$errors, array $data, string $field, array $allowedKeys, string $message, bool $upperCase = false): void
    {
        if (empty($data[$field])) {
            return;
        }

        $value = (string) $data[$field];
        $key = $upperCase
            ? strtoupper(str_replace(' ', '', $value))
            : FormHelper::choiceKey($value);

        if (!in_array($key, $allowedKeys, true)) {
            $errors[$field][] = $message;
        }
    }

    private static function validateBloodType(array &$errors, array $data, string $field): void
    {
        if (empty($data[$field])) {
            return;
        }

        $value = FormHelper::normalizeBloodType($data[$field]);

        if (!in_array($value, FormHelper::validBloodTypes(), true)) {
            $errors[$field][] = 'Tipo de sangre invalido.';
        }
    }

    private static function validateSemester(array &$errors, array $data, string $field, array $allowedKeys): void
    {
        if (empty($data[$field])) {
            return;
        }

        $semester = FormHelper::normalizeSemester($data[$field]);

        if (!in_array($semester, FormHelper::validSemesters(), true) || !in_array($semester, $allowedKeys, true)) {
            $errors[$field][] = 'El semestre seleccionado no es valido.';
        }
    }
}
