<?php
declare(strict_types=1);

class Inscripcion
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
        public string $madreNombre = '',
        public string $madreTelefono = '',
        public string $padreNombre = '',
        public string $padreTelefono = '',
        public string $tutorDomicilio = '',
        public string $tutorIne = '',
        public string $tutorCurp = '',
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
            madreNombre: FormHelper::text(FormHelper::first($data, ['madre_nombre', 'madreNombre'])),
            madreTelefono: FormHelper::phone(FormHelper::first($data, ['madre_telefono', 'madreTelefono'])),
            padreNombre: FormHelper::text(FormHelper::first($data, ['padre_nombre', 'padreNombre'])),
            padreTelefono: FormHelper::phone(FormHelper::first($data, ['padre_telefono', 'padreTelefono'])),
            tutorDomicilio: FormHelper::text(FormHelper::first($data, ['tutor_domicilio', 'tutorDomicilio'])),
            tutorIne: FormHelper::upper(FormHelper::first($data, ['tutor_ine', 'tutorIne'])),
            tutorCurp: FormHelper::upper(FormHelper::first($data, ['tutor_curp', 'tutorCurp'])),
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
            'madre_nombre' => $this->madreNombre,
            'madre_telefono' => $this->madreTelefono,
            'padre_nombre' => $this->padreNombre,
            'padre_telefono' => $this->padreTelefono,
            'tutor_domicilio' => $this->tutorDomicilio,
            'tutor_ine' => $this->tutorIne,
            'tutor_curp' => $this->tutorCurp,
            'periodo_escolar' => $this->periodoEscolar,
            'semestre' => $this->semestre,
            'especialidad' => $this->especialidad,
            'created_at' => $this->createdAt,
        ];
    }
}
