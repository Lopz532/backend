<?php
declare(strict_types=1);

class GoogleSheetsService
{
    private array $config;

    public function __construct(?array $config = null)
    {
        $this->config = $config ?? require __DIR__ . '/../config/googleSheets.php';
    }

    public function append(string $sheetName, array $data, array $meta = []): array
    {
        if (!$this->config['enabled']) {
            return [
                'success' => true,
                'stored' => false,
                'message' => 'Google Sheets no esta habilitado',
                'body' => [],
            ];
        }

        $url = trim((string) ($this->config['web_app_url'] ?? ''));

        if ($url === '') {
            return [
                'success' => true,
                'stored' => false,
                'message' => 'La URL de Google Apps Script no esta configurada',
                'body' => [],
            ];
        }

        $payload = [
            'action' => 'append',
            'sheet' => $sheetName,
            'data' => $data,
            'meta' => array_merge([
                'request_id' => SecurityService::generateRequestId(),
                'created_at' => gmdate('c'),
            ], $meta),
        ];

        $jsonPayload = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($jsonPayload === false) {
            return [
                'success' => false,
                'message' => 'No se pudo serializar el payload',
            ];
        }

        $response = $this->sendRequest($url, $jsonPayload);

        if (!$response['success']) {
            return $response;
        }

        return [
            'success' => true,
            'stored' => true,
            'message' => 'Registro enviado correctamente a Google Sheets',
            'response' => $response['body'],
        ];
    }

    private function sendRequest(string $url, string $payload): array
    {
        $timeout = (int) ($this->config['timeout'] ?? 15);

        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json; charset=utf-8',
                    'Accept: application/json',
                ],
                CURLOPT_CONNECTTIMEOUT => $timeout,
                CURLOPT_TIMEOUT => $timeout,
            ]);

            $body = curl_exec($ch);
            $curlError = curl_error($ch);
            $statusCode = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
            curl_close($ch);

            if ($body === false) {
                return [
                    'success' => false,
                    'message' => 'Error al conectar con Google Sheets: ' . $curlError,
                ];
            }

            return $this->normalizeResponse((string) $body, $statusCode);
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/json; charset=utf-8\r\nAccept: application/json\r\n",
                'content' => $payload,
                'timeout' => $timeout,
            ],
        ]);

        $body = @file_get_contents($url, false, $context);

        if ($body === false) {
            return [
                'success' => false,
                'message' => 'No se pudo enviar la solicitud a Google Sheets',
            ];
        }

        return $this->normalizeResponse($body, 200);
    }

    private function normalizeResponse(string $body, int $statusCode): array
    {
        error_log('GS_STATUS='.$statusCode);
        error_log('GS_BODY='.$body);
        $decoded = json_decode($body, true);

        if ($statusCode >= 200 && $statusCode < 300) {
            return [
                'success' => true,
                'status_code' => $statusCode,
                'body' => is_array($decoded) ? $decoded : ['raw' => $body],
            ];
        }

        return [
            'success' => false,
            'status_code' => $statusCode,
            'message' => is_array($decoded) && isset($decoded['message']) ? (string) $decoded['message'] : 'Google Sheets respondio con error',
            'body' => is_array($decoded) ? $decoded : ['raw' => $body],
        ];
    }
}
