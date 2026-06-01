<?php
declare(strict_types=1);

class ImssController
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

        $imss = Imss::fromArray($request);
        $sheetName = $this->sheetConfig['sheets']['imss'] ?? 'IMSS';
        $result = $this->sheets->append($sheetName, $imss->toArray(), [
            'module' => 'imss',
        ]);

        if (!$result['success']) {
            ResponseService::error(
                $result['message'] ?? 'No se pudo guardar el registro IMSS.',
                502,
                [],
                ['provider' => 'google_sheets']
            );
        }

        ResponseService::created('Registro IMSS guardado correctamente.', [
            'imss' => $imss->toArray(),
            'sheets' => $result['response'] ?? [],
            'stored' => (bool) ($result['stored'] ?? false),
        ]);
    }

    private function validate(array $data): array
    {
        $errors = [];

        foreach (['nombre', 'matricula', 'nss', 'curp'] as $field) {
            if (empty($data[$field])) {
                $errors[$field][] = 'El campo ' . $field . ' es obligatorio.';
            }
        }

        if (!empty($data['correo']) && !filter_var((string) $data['correo'], FILTER_VALIDATE_EMAIL)) {
            $errors['correo'][] = 'El correo electronico no es valido.';
        }

        if (!empty($data['nss']) && strlen(preg_replace('/\D/', '', (string) $data['nss']) ?? '') < 11) {
            $errors['nss'][] = 'El NSS no es valido.';
        }

        return $errors;
    }
}
