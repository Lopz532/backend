<?php
declare(strict_types=1);

class Usuario
{
    public function __construct(
        public string $usuario = '',
        public string $password = '',
        public string $rol = 'operador',
        public bool $activo = true
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            usuario: (string) ($data['usuario'] ?? ''),
            password: (string) ($data['password'] ?? ''),
            rol: (string) ($data['rol'] ?? 'operador'),
            activo: filter_var($data['activo'] ?? true, FILTER_VALIDATE_BOOLEAN)
        );
    }

    public function toArray(bool $includePassword = false): array
    {
        $data = [
            'usuario' => $this->usuario,
            'rol' => $this->rol,
            'activo' => $this->activo,
        ];

        if ($includePassword) {
            $data['password'] = $this->password;
        }

        return $data;
    }
}
