<?php
declare(strict_types=1);

class Beca
{
    public function __construct(
        public string $nombre = '',
        public string $matricula = '',
        public string $programa = '',
        public string $promedio = '',
        public string $ingresoFamiliar = '',
        public string $motivo = '',
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
            programa: (string) ($data['programa'] ?? ''),
            promedio: (string) ($data['promedio'] ?? ''),
            ingresoFamiliar: (string) ($data['ingreso_familiar'] ?? ''),
            motivo: (string) ($data['motivo'] ?? ''),
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
            'programa' => $this->programa,
            'promedio' => $this->promedio,
            'ingreso_familiar' => $this->ingresoFamiliar,
            'motivo' => $this->motivo,
            'correo' => $this->correo,
            'telefono' => $this->telefono,
            'created_at' => $this->createdAt,
        ];
    }
}
