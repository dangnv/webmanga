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
    'google' => [
        'client_id' => '752175406099-6bojjpupulim9lf9hemjis5596vb6tkp.apps.googleusercontent.com',
        'client_secret' => '1aK0H9CW7L96MWMzfXR3R5RH',
        'redirect' => '/callback/google',
    ],
    'facebook' => [
        'client_id' => '408056310610644',
        'client_secret' => 'e68db5a989a05f151feacb2f2db82e45',
        'redirect' => '/callback/facebook',
    ],
];
