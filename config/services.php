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
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'newsapi' => [
        'key' => env('NEWSAPI_KEY'),
        'url' => env('NEWSAPI_URL', 'https://newsapi.org/v2/top-headlines'),
    ],

    'nytimes' => [
        'key' => env('NYTIMES_API_KEY'),
        'url' => env('NYTIMES_API_URL', 'https://api.nytimes.com/svc/topstories/v2/home.json'),
    ],

    'guardian' => [
        'key' => env('GARDIAN_API_KEY'),
        'url' => env('GARDIAN_API_URL', 'https://content.guardianapis.com/search'),
    ],

];
