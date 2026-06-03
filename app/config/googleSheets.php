<?php
declare(strict_types=1);

return [
    'enabled' => filter_var(getenv('GOOGLE_SHEETS_ENABLED') ?: 'false', FILTER_VALIDATE_BOOLEAN),
    'web_app_url' => getenv('GOOGLE_SHEETS_WEB_APP_URL') ?: '',
    'timeout' => (int) (getenv('GOOGLE_SHEETS_TIMEOUT') ?: 15),
    'default_sheet' => 'Registros',
    'sheets' => [
        'alumnos' => 'Alumnos',
        'becas' => 'Becas',
        'imss' => 'IMSS',
        'fichas' => 'Fichas',
        'inscripciones' => 'Inscripciones',
        'auth' => 'Auth',
    ],
];
