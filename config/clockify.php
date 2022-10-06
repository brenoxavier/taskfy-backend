<?php

use App\Utilitarios;

return [
    'api_key' => Utilitarios::getEnvironmentVariable('CLOCKIFY_API_KEY'),
    'workspace_id' => Utilitarios::getEnvironmentVariable('CLOCKIFY_WORKSPACE_ID'),
    'api_url' => Utilitarios::getEnvironmentVariable('CLOCKIFY_API_URL')
];
