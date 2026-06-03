<?php
declare(strict_types=1);

class InscripcionController
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
        $errors = InscripcionValidator::validate($request);

        if (!empty($errors)) {
            ResponseService::validation($errors);
        }

        $inscripcion = Inscripcion::fromArray($request);
        $sheetName = $this->sheetConfig['sheets']['inscripciones'] ?? 'Inscripciones';
        $result = $this->sheets->append($sheetName, $inscripcion->toArray(), [
            'module' => 'inscripciones',
        ]);

        if (!$result['success']) {
            ResponseService::error(
                $result['message'] ?? 'No se pudo guardar la inscripcion.',
                502,
                [],
                ['provider' => 'google_sheets']
            );
        }

        ResponseService::created('Inscripcion registrada correctamente.', [
            'inscripcion' => $inscripcion->toArray(),
            'sheets' => $result['response'] ?? [],
            'stored' => (bool) ($result['stored'] ?? false),
        ]);
    }
}
