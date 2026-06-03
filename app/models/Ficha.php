<?php
declare(strict_types=1);

class Ficha
{
    public function __construct(
        public string $nombre = '',
        public string $segundoNombre = '',
        public string $apellidoMaterno = '',
        public string $apellidoPaterno = '',
        public string $curp = '',
        public string $fechaNacimiento = '',
        public string $lugarNacimiento = '',
        public string $lugarProcedencia = '',
        public string $edad = '',
        public string $domicilio = '',
        public string $telefono = '',
        public string $genero = '',
        public string $tipoSangre = '',
        public string $secundariaProcedencia = '',
        public string $correoElectronico = '',
        public string $tutorNombre = '',
        public string $tutorDomicilio = '',
        public string $tutorTelefono = '',
        public string $tutorLugarProcedencia = '',
        public string $periodoEscolar = '',
        public string $semestre = '',
        public string $especialidad = '',
        public string $createdAt = ''
    ) {
        $this->createdAt = $this->createdAt !== '' ? $this->createdAt : gmdate('c');
    }

    public static function fromArray(array $data): self
    {
        $formsConfig = require __DIR__ . '/../config/schoolForms.php';
        $dropdowns = $formsConfig['dropdowns'] ?? [];

        return new self(
            nombre: FormHelper::text(FormHelper::first($data, ['nombre'])),
            segundoNombre: FormHelper::text(FormHelper::first($data, ['segundo_nombre', 'segundoNombre'])),
            apellidoMaterno: FormHelper::text(FormHelper::first($data, ['apellido_materno', 'apellidoMaterno'])),
            apellidoPaterno: FormHelper::text(FormHelper::first($data, ['apellido_paterno', 'apellidoPaterno'])),
            curp: FormHelper::upper(FormHelper::first($data, ['curp'])),
            fechaNacimiento: FormHelper::text(FormHelper::first($data, ['fecha_nacimiento', 'fechaNacimiento'])),
            lugarNacimiento: FormHelper::text(FormHelper::first($data, ['lugar_nacimiento', 'lugarNacimiento'])),
            lugarProcedencia: FormHelper::text(FormHelper::first($data, ['lugar_procedencia', 'lugarProcedencia'])),
            edad: FormHelper::text(FormHelper::first($data, ['edad'])),
            domicilio: FormHelper::text(FormHelper::first($data, ['domicilio'])),
            telefono: FormHelper::phone(FormHelper::first($data, ['telefono'])),
            genero: FormHelper::resolveChoice($dropdowns['genero'] ?? [], FormHelper::first($data, ['genero'])) ?? FormHelper::text(FormHelper::first($data, ['genero'])),
            tipoSangre: FormHelper::resolveBloodType($dropdowns['tipo_sangre'] ?? [], FormHelper::first($data, ['tipo_sangre', 'tipoSangre'])) ?? FormHelper::text(FormHelper::first($data, ['tipo_sangre', 'tipoSangre'])),
            secundariaProcedencia: FormHelper::text(FormHelper::first($data, ['secundaria_procedencia', 'secundariaProcedencia'])),
            correoElectronico: FormHelper::email(FormHelper::first($data, ['correo_electronico', 'correoElectronico', 'correo'])),
            tutorNombre: FormHelper::text(FormHelper::first($data, ['tutor_nombre', 'tutorNombre'])),
            tutorDomicilio: FormHelper::text(FormHelper::first($data, ['tutor_domicilio', 'tutorDomicilio'])),
            tutorTelefono: FormHelper::phone(FormHelper::first($data, ['tutor_telefono', 'tutorTelefono'])),
            tutorLugarProcedencia: FormHelper::text(FormHelper::first($data, ['tutor_lugar_procedencia', 'tutorLugarProcedencia'])),
            periodoEscolar: FormHelper::resolveChoice($dropdowns['periodo_escolar'] ?? [], FormHelper::first($data, ['periodo_escolar', 'periodoEscolar'])) ?? FormHelper::text(FormHelper::first($data, ['periodo_escolar', 'periodoEscolar'])),
            semestre: FormHelper::resolveSemester($dropdowns['semestre'] ?? [], FormHelper::first($data, ['semestre'])) ?? FormHelper::text(FormHelper::first($data, ['semestre'])),
            especialidad: FormHelper::resolveChoice($dropdowns['especialidad'] ?? [], FormHelper::first($data, ['especialidad'])) ?? FormHelper::text(FormHelper::first($data, ['especialidad'])),
            createdAt: FormHelper::text(FormHelper::first($data, ['created_at'], gmdate('c')))
        );
    }

    public function toArray(): array
    {
        return [
            'nombre' => $this->nombre,
            'segundo_nombre' => $this->segundoNombre,
            'apellido_materno' => $this->apellidoMaterno,
            'apellido_paterno' => $this->apellidoPaterno,
            'curp' => $this->curp,
            'fecha_nacimiento' => $this->fechaNacimiento,
            'lugar_nacimiento' => $this->lugarNacimiento,
            'lugar_procedencia' => $this->lugarProcedencia,
            'edad' => $this->edad,
            'domicilio' => $this->domicilio,
            'telefono' => $this->telefono,
            'genero' => $this->genero,
            'tipo_sangre' => $this->tipoSangre,
            'secundaria_procedencia' => $this->secundariaProcedencia,
            'correo_electronico' => $this->correoElectronico,
            'tutor_nombre' => $this->tutorNombre,
            'tutor_domicilio' => $this->tutorDomicilio,
            'tutor_telefono' => $this->tutorTelefono,
            'tutor_lugar_procedencia' => $this->tutorLugarProcedencia,
            'periodo_escolar' => $this->periodoEscolar,
            'semestre' => $this->semestre,
            'especialidad' => $this->especialidad,
            'created_at' => $this->createdAt,
        ];
    }
}
