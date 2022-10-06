<?php

include_once "helper.php";

return [
    'api_key' => getEnvironmentVariable('CLOCKIFY_API_KEY'),
    'workspace_id' => getEnvironmentVariable('CLOCKIFY_WORKSPACE_ID'),
    'api_url' => getEnvironmentVariable('CLOCKIFY_API_URL')
];
