<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => getenv('MAILGUN_DOMAIN') ?? env('MAILGUN_DOMAIN'),
        'secret' => getenv('MAILGUN_SECRET') ?? env('MAILGUN_SECRET'),
        'endpoint' => getenv('MAILGUN_ENDPOINT') ?? env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => getenv('POSTMARK_TOKEN') ?? env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => getenv('AWS_ACCESS_KEY_ID') ?? env('AWS_ACCESS_KEY_ID'),
        'secret' => getenv('AWS_SECRET_ACCESS_KEY') ?? env('AWS_SECRET_ACCESS_KEY'),
        'region' => getenv('AWS_DEFAULT_REGION') ?? env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

];
