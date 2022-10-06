<?php

use App\Utilitarios;

return [
    'api_key' => Utilitarios::getEnvironmentVariable('FERIADO_API_KEY'),
    'api_url' => Utilitarios::getEnvironmentVariable('FERIADO_API_URL'),
    'cidade_ibge' => Utilitarios::getEnvironmentVariable('FERIADO_CIDADE_IBGE'),
];
