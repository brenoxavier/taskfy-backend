<?php

return [
    'api_key' => getenv('CLOCKIFY_API_KEY') ?? env('CLOCKIFY_API_KEY'),
    'workspace_id' => getenv('CLOCKIFY_WORKSPACE_ID') ?? env('CLOCKIFY_WORKSPACE_ID'),
    'api_url' => getenv('CLOCKIFY_API_URL') ?? env('CLOCKIFY_API_URL')
];
