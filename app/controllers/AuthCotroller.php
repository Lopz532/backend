<?php
declare(strict_types=1);

class AuthController
{
    public function login(array $request = []): void
    {
        $errors = AuthValidator::validateLogin($request);

        if (!empty($errors)) {
            ResponseService::validation($errors);
        }

        $expectedUser = (string) SecurityService::env('API_USER', '');
        $expectedPassword = (string) SecurityService::env('API_PASSWORD', '');

        $usuario = (string) ($request['usuario'] ?? '');
        $password = (string) ($request['password'] ?? '');

        if ($expectedUser === '' || $expectedPassword === '') {
            ResponseService::error('La autenticacion no esta configurada en el servidor.', 503);
        }

        if (!hash_equals($expectedUser, $usuario) || !hash_equals($expectedPassword, $password)) {
            ResponseService::error('Credenciales invalidas.', 401);
        }

        $token = SecurityService::generateToken([
            'sub' => $usuario,
            'role' => 'operador',
        ]);

        ResponseService::success('Autenticacion exitosa.', [
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => 7200,
            'user' => [
                'usuario' => $usuario,
                'rol' => 'operador',
            ],
        ]);
    }
}

class_alias(AuthController::class, 'AuthCotroller');
