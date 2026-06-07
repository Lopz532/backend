<?php
declare(strict_types=1);

class JsonHelper
{
    private static ?string $rawInput = null;

    public static function rawInput(): string
    {
        if (self::$rawInput === null) {
            $body = file_get_contents('php://input');
            self::$rawInput = is_string($body) ? $body : '';
        }

        return self::$rawInput;
    }

    public static function decodeInput(): array
    {
        $raw = trim(self::rawInput());

        if ($raw === '') {
            return self::sanitizeArray($_POST);
        }

        $decoded = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            return self::sanitizeArray($_POST);
        }

        return self::sanitizeArray($decoded);
    }

    public static function isJsonRequest(): bool
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? '';

        return stripos($contentType, 'application/json') !== false;
    }

    public static function sanitizeArray(array $data): array
    {
        $clean = [];

        foreach ($data as $key => $value) {
            $safeKey = is_string($key) ? trim($key) : (string) $key;

            if (is_array($value)) {
                $clean[$safeKey] = self::sanitizeArray($value);
                continue;
            }

            if (is_string($value)) {
                $clean[$safeKey] = self::normalizeFieldByKey($safeKey, $value);
                continue;
            }

            $clean[$safeKey] = $value;
        }

        return $clean;
    }

    public static function sanitizeString(string $value): string
    {
        $value = trim($value);
        $value = strip_tags($value);
        $value = preg_replace('/[\x00-\x1F\x7F]/u', '', $value) ?? $value;

        return $value;
    }

    private static function normalizeFieldByKey(string $key, string $value): string
    {
        $normalizedKey = strtolower($key);

        if (in_array($normalizedKey, ['fecha_nacimiento', 'fechanacimiento', 'fechaNacimiento'], true)) {
            return FormHelper::normalizeDate($value);
        }

        if (in_array($normalizedKey, ['tipo_sangre', 'tiposangre', 'tipoSangre'], true)) {
            return FormHelper::normalizeBloodType($value);
        }

        if ($normalizedKey === 'genero') {
            return FormHelper::normalizeGender($value);
        }

        if ($normalizedKey === 'semestre') {
            return FormHelper::normalizeSemester($value);
        }

        return self::sanitizeString($value);
    }
}
