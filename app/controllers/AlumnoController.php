<?php
declare(strict_types=1);

class AlumnoController
{
    private GoogleSheetsService $sheets;
    private array $sheetConfig;

    public function __construct()
    {
        $this->sheetConfig = require __DIR__ . '/../config/googleSheets.php';
        $this->sheets = new GoogleSheetsService($this->sheetConfig);
    }

    public function registrar(array $request = []): void
    {
        $errors = AlumnoValidator::validate($request);

        if (!empty($errors)) {
            ResponseService::validation($errors);
        }

        $alumno = Alumno::fromArray($request);
        $sheetName = $this->sheetConfig['sheets']['alumnos'] ?? 'Alumnos';
        $result = $this->sheets->append($sheetName, $alumno->toArray(), [
            'module' => 'alumnos',
        ]);

        if (!$result['success']) {
            ResponseService::error(
                $result['message'] ?? 'No se pudo guardar el alumno.',
                502,
                [],
                ['provider' => 'google_sheets']
            );
        }

        ResponseService::created('Alumno registrado correctamente.', [
            'alumno' => $alumno->toArray(),
            'sheets' => $result['response'] ?? [],
            'stored' => (bool) ($result['stored'] ?? false),
        ]);
    }
}
