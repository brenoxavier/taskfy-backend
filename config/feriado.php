<?php

return [
    'api_key' => getenv('FERIADO_API_KEY') ?? env('FERIADO_API_KEY'),
    'api_url' => getenv('FERIADO_API_URL') ?? env('FERIADO_API_URL'),
    'cidade_ibge' => getenv('FERIADO_CIDADE_IBGE') ?? env('FERIADO_CIDADE_IBGE'),
];
