<?php
declare(strict_types=1);

class FichaController
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
        ResponseService::error('PRUEBA123',400);
        error_log('REQUEST:' .json_encode($request,JSON_UNESCAPED_UNICODE));
        $errors = FichaValidator::validate($request);

        if (!empty($errors)) {
            ResponseService::validation($errors);
        }

        $ficha = Ficha::fromArray($request);
        $sheetName = $this->sheetConfig['sheets']['fichas'] ?? 'Fichas';
        $result = $this->sheets->append($sheetName, $ficha->toArray(), [
            'module' => 'fichas',
        ]);

        if (!$result['success']) {
            ResponseService::error(
                $result['message'] ?? 'No se pudo guardar la ficha.',
                502,
                [],
                ['provider' => 'google_sheets']
            );
        }

        ResponseService::created('Ficha registrada correctamente.', [
            'ficha' => $ficha->toArray(),
            'sheets' => $result['response'] ?? [],
            'stored' => (bool) ($result['stored'] ?? false),
        ]);
    }
}
