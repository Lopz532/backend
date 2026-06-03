<?php
declare(strict_types=1);

class FormHelper
{
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
        $key = self::bloodTypeKey($value);

        if (array_key_exists($key, $choices)) {
            return (string) $choices[$key];
        }

        return $fallback;
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
