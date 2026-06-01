<?php
declare(strict_types=1);

class AuthMiddleware
{
    public static function check(): bool
    {
        if (SecurityService::isValidApiKey(SecurityService::extractApiKey())) {
            return true;
        }

        $token = SecurityService::extractBearerToken();

        if (is_string($token) && $token !== '') {
            $result = SecurityService::verifyToken($token);

            if (($result['valid'] ?? false) === true) {
                return true;
            }
        }

        ResponseService::error('No autorizado.', 401);

        return false;
    }
}
