<?php
declare(strict_types=1);

class FormHelper
{
    private const BLOOD_TYPES = [
        'O+' => 'O+',
        'O-' => 'O-',
        'A+' => 'A+',
        'A-' => 'A-',
        'B+' => 'B+',
        'B-' => 'B-',
        'AB+' => 'AB+',
        'AB-' => 'AB-',
        'O' => 'O',
        'A' => 'A',
        'B' => 'B',
        'AB' => 'AB',
    ];

    public static function first(array $data, array $keys, mixed $default = null): mixed
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $data)) {
                return $data[$key];
            }
        }

        return $default;
    }

    public static function text(mixed $value): string
    {
        return JsonHelper::sanitizeString((string) $value);
    }

    public static function email(mixed $value): string
    {
        return strtolower(trim((string) $value));
    }

    public static function phone(mixed $value): string
    {
        return preg_replace('/[^0-9+]/', '', (string) $value) ?? '';
    }

    public static function normalizeDate(mixed $value): string
    {
        $parsed = self::parseDate($value);

        if ($parsed !== null) {
            return $parsed;
        }

        return self::text($value);
    }

    public static function parseDate(mixed $value): ?string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        $formats = ['Y-m-d', 'd/m/Y', 'j/m/Y', 'd/n/Y', 'j/n/Y'];

        foreach ($formats as $format) {
            $date = DateTimeImmutable::createFromFormat('!' . $format, $value);

            if ($date === false) {
                continue;
            }

            $errors = DateTimeImmutable::getLastErrors();
            $warningCount = is_array($errors) ? (int) ($errors['warning_count'] ?? 0) : 0;
            $errorCount = is_array($errors) ? (int) ($errors['error_count'] ?? 0) : 0;

            if ($warningCount === 0 && $errorCount === 0) {
                return $date->format('Y-m-d');
            }
        }

        return null;
    }

    public static function normalizeBloodType(mixed $value): string
    {
        $value = strtoupper(str_replace([' ', '.', "\t", "\n", "\r"], '', self::text($value)));

        if ($value === '') {
            return '';
        }

        return self::BLOOD_TYPES[$value] ?? $value;
    }

    public static function normalizeSemester(mixed $value): string
    {
        $value = self::text($value);

        if ($value === '') {
            return '';
        }

        if (preg_match('/([1-6])/', $value, $matches) === 1) {
            return $matches[1];
        }

        return $value;
    }

    public static function validBloodTypes(): array
    {
        return array_values(self::BLOOD_TYPES);
    }

    public static function validSemesters(): array
    {
        return ['1', '2', '3', '4', '5', '6'];
    }

    public static function upper(mixed $value): string
    {
        return strtoupper(self::text($value));
    }

    public static function choiceKey(mixed $value): string
    {
        $value = self::text($value);
        $value = self::ascii($value);
        $value = strtolower($value);
        $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? $value;

        return trim($value, '-');
    }

    public static function semesterKey(mixed $value): string
    {
        $value = self::text($value);

        if (preg_match('/([1-6])/', $value, $matches) === 1) {
            return $matches[1];
        }

        return self::choiceKey($value);
    }

    public static function bloodTypeKey(mixed $value): string
    {
        $value = self::text($value);

        return strtoupper(str_replace(' ', '', $value));
    }

    public static function resolveChoice(array $choices, mixed $value, ?string $fallback = null): ?string
    {
        $key = self::choiceKey($value);

        if (array_key_exists($key, $choices)) {
            return (string) $choices[$key];
        }

        return $fallback;
    }

    public static function resolveSemester(array $choices, mixed $value, ?string $fallback = null): ?string
    {
        $key = self::semesterKey($value);

        if (array_key_exists($key, $choices)) {
            return (string) $choices[$key];
        }

        return $fallback;
    }

    public static function resolveBloodType(array $choices, mixed $value, ?string $fallback = null): ?string
    {
        $normalized = self::normalizeBloodType($value);
        $key = self::bloodTypeKey($normalized);

        if (array_key_exists($key, $choices)) {
            return (string) $choices[$key];
        }

        if (array_key_exists($normalized, $choices)) {
            return (string) $choices[$normalized];
        }

        return $normalized !== '' ? $normalized : $fallback;
    }

    private static function ascii(string $value): string
    {
        if (function_exists('iconv')) {
            $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);

            if (is_string($converted) && $converted !== '') {
                return $converted;
            }
        }

        return $value;
    }
}
