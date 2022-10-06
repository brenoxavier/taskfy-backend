<?php

use App\Utilitarios;

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
        'domain' => Utilitarios::getEnvironmentVariable('MAILGUN_DOMAIN'),
        'secret' => Utilitarios::getEnvironmentVariable('MAILGUN_SECRET'),
        'endpoint' => Utilitarios::getEnvironmentVariable('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => Utilitarios::getEnvironmentVariable('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => Utilitarios::getEnvironmentVariable('AWS_ACCESS_KEY_ID'),
        'secret' => Utilitarios::getEnvironmentVariable('AWS_SECRET_ACCESS_KEY'),
        'region' => Utilitarios::getEnvironmentVariable('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

];
