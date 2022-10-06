<?php

include_once "helper.php";

return [
    'api_key' => getEnvironmentVariable('FERIADO_API_KEY'),
    'api_url' => getEnvironmentVariable('FERIADO_API_URL'),
    'cidade_ibge' => getEnvironmentVariable('FERIADO_CIDADE_IBGE'),
];
