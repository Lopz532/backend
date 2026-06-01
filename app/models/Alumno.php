<?php
declare(strict_types=1);

class Alumno
{
    public function __construct(
        public string $nombre = '',
        public string $matricula = '',
        public string $grupo = '',
        public string $grado = '',
        public string $telefono = '',
        public string $correo = '',
        public string $tutor = '',
        public string $observaciones = '',
        public string $createdAt = ''
    ) {
        $this->createdAt = $this->createdAt !== '' ? $this->createdAt : gmdate('c');
    }

    public static function fromArray(array $data): self
    {
        return new self(
            nombre: (string) ($data['nombre'] ?? ''),
            matricula: (string) ($data['matricula'] ?? ''),
            grupo: (string) ($data['grupo'] ?? ''),
            grado: (string) ($data['grado'] ?? ''),
            telefono: (string) ($data['telefono'] ?? ''),
            correo: (string) ($data['correo'] ?? ''),
            tutor: (string) ($data['tutor'] ?? ''),
            observaciones: (string) ($data['observaciones'] ?? ''),
            createdAt: (string) ($data['created_at'] ?? gmdate('c'))
        );
    }

    public function toArray(): array
    {
        return [
            'nombre' => $this->nombre,
            'matricula' => $this->matricula,
            'grupo' => $this->grupo,
            'grado' => $this->grado,
            'telefono' => $this->telefono,
            'correo' => $this->correo,
            'tutor' => $this->tutor,
            'observaciones' => $this->observaciones,
            'created_at' => $this->createdAt,
        ];
    }
}
