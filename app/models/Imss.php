<?php
declare(strict_types=1);

class Imss
{
    public function __construct(
        public string $nombre = '',
        public string $matricula = '',
        public string $nss = '',
        public string $curp = '',
        public string $correo = '',
        public string $telefono = '',
        public string $createdAt = ''
    ) {
        $this->createdAt = $this->createdAt !== '' ? $this->createdAt : gmdate('c');
    }

    public static function fromArray(array $data): self
    {
        return new self(
            nombre: (string) ($data['nombre'] ?? ''),
            matricula: (string) ($data['matricula'] ?? ''),
            nss: (string) ($data['nss'] ?? ''),
            curp: (string) ($data['curp'] ?? ''),
            correo: (string) ($data['correo'] ?? ''),
            telefono: (string) ($data['telefono'] ?? ''),
            createdAt: (string) ($data['created_at'] ?? gmdate('c'))
        );
    }

    public function toArray(): array
    {
        return [
            'nombre' => $this->nombre,
            'matricula' => $this->matricula,
            'nss' => $this->nss,
            'curp' => $this->curp,
            'correo' => $this->correo,
            'telefono' => $this->telefono,
            'created_at' => $this->createdAt,
        ];
    }
}
