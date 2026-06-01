<?php
declare(strict_types=1);

class SecurityService
{
    public static function env(string $key, mixed $default = null): mixed
    {
        $value = getenv($key);

        return $value === false || $value === '' ? $default : $value;
    }

    public static function generateRequestId(): string
    {
        return bin2hex(random_bytes(16));
    }

    public static function apiKey(): string
    {
        return (string) self::env('API_KEY', '');
    }

    public static function isValidApiKey(?string $apiKey = null): bool
    {
        $provided = $apiKey ?? self::extractApiKey();
        $expected = self::apiKey();

        if ($expected === '') {
            return false;
        }

        return is_string($provided) && hash_equals($expected, $provided);
    }

    public static function extractBearerToken(): ?string
    {
        $authorization = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';

        if (preg_match('/Bearer\s+(.*)$/i', $authorization, $matches) === 1) {
            return trim($matches[1]);
        }

        return null;
    }

    public static function extractApiKey(): ?string
    {
        $headers = self::requestHeaders();

        foreach (['x-api-key', 'api-key'] as $header) {
            if (isset($headers[$header]) && $headers[$header] !== '') {
                return (string) $headers[$header];
            }
        }

        return null;
    }

    public static function generateToken(array $claims = [], int $ttlSeconds = 7200): string
    {
        $secret = (string) self::env('APP_SECRET', 'change-this-secret');
        $payload = array_merge($claims, [
            'iat' => time(),
            'exp' => time() + $ttlSeconds,
            'jti' => self::generateRequestId(),
        ]);

        $encodedPayload = self::base64UrlEncode((string) json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $signature = hash_hmac('sha256', $encodedPayload, $secret);

        return $encodedPayload . '.' . $signature;
    }

    public static function verifyToken(string $token): array
    {
        $secret = (string) self::env('APP_SECRET', 'change-this-secret');
        $parts = explode('.', $token, 2);

        if (count($parts) !== 2) {
            return ['valid' => false, 'payload' => []];
        }

        [$encodedPayload, $signature] = $parts;
        $expectedSignature = hash_hmac('sha256', $encodedPayload, $secret);

        if (!hash_equals($expectedSignature, $signature)) {
            return ['valid' => false, 'payload' => []];
        }

        $payload = json_decode(self::base64UrlDecode($encodedPayload), true);

        if (!is_array($payload)) {
            return ['valid' => false, 'payload' => []];
        }

        if (isset($payload['exp']) && is_numeric($payload['exp']) && (int) $payload['exp'] < time()) {
            return ['valid' => false, 'payload' => $payload];
        }

        return ['valid' => true, 'payload' => $payload];
    }

    public static function sanitizeText(mixed $value): string
    {
        return JsonHelper::sanitizeString((string) $value);
    }

    public static function normalizePhone(mixed $value): string
    {
        return preg_replace('/[^0-9+]/', '', (string) $value) ?? '';
    }

    public static function normalizeEmail(mixed $value): string
    {
        return strtolower(trim((string) $value));
    }

    private static function requestHeaders(): array
    {
        if (function_exists('getallheaders')) {
            $headers = getallheaders();

            if (is_array($headers)) {
                $normalized = [];

                foreach ($headers as $name => $value) {
                    $normalized[strtolower((string) $name)] = is_string($value) ? trim($value) : (string) $value;
                }

                return $normalized;
            }
        }

        $normalized = [];

        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $header = strtolower(str_replace('_', '-', substr($key, 5)));
                $normalized[$header] = is_string($value) ? trim($value) : (string) $value;
            }
        }

        return $normalized;
    }

    private static function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private static function base64UrlDecode(string $value): string
    {
        $remainder = strlen($value) % 4;

        if ($remainder !== 0) {
            $value .= str_repeat('=', 4 - $remainder);
        }

        return (string) base64_decode(strtr($value, '-_', '+/'));
    }
}
