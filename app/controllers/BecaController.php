<?php
declare(strict_types=1);

class BecaController
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
        $errors = $this->validate($request);

        if (!empty($errors)) {
            ResponseService::validation($errors);
        }

        $beca = Beca::fromArray($request);
        $sheetName = $this->sheetConfig['sheets']['becas'] ?? 'Becas';
        $result = $this->sheets->append($sheetName, $beca->toArray(), [
            'module' => 'becas',
        ]);

        if (!$result['success']) {
            ResponseService::error(
                $result['message'] ?? 'No se pudo guardar la solicitud de beca.',
                502,
                [],
                ['provider' => 'google_sheets']
            );
        }

        ResponseService::created('Solicitud de beca registrada correctamente.', [
            'beca' => $beca->toArray(),
            'sheets' => $result['response'] ?? [],
            'stored' => (bool) ($result['stored'] ?? false),
        ]);
    }

    private function validate(array $data): array
    {
        $errors = [];

        foreach (['nombre', 'matricula', 'programa', 'motivo'] as $field) {
            if (empty($data[$field])) {
                $errors[$field][] = 'El campo ' . $field . ' es obligatorio.';
            }
        }

        if (!empty($data['correo']) && !filter_var((string) $data['correo'], FILTER_VALIDATE_EMAIL)) {
            $errors['correo'][] = 'El correo electronico no es valido.';
        }

        return $errors;
    }
}
