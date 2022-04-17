<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    // 'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'paths' => ['api/*'],

    'allowed_methods' => [
        'POST',
        'GET',
        'OPTIONS',
        'PUT',
        'PATCH',
        'DELETE',
    ],


    'allowed_origins' => ['https://apidevportal.bi.go.id'],

    'allowed_origins_patterns' => [],

    // 'allowed_headers' => ['*'],
    'allowed_headers' => [
        'X-TIMESTAMP',
        'X-CLIENT-KEY',
        'X-CLIENT-SECRET',
        'Content-Type',
        'X-SIGNATURE',
        'Accept',
        'Authorization',
        'Authorization-Customer',
        'ORIGIN',
        'X-PARTNER-ID',
        'X-EXTERNAL-ID',
        'X-IP-ADDRESS',
        'X-DEVICE-ID',
        'CHANNEL-ID',
        'X-LATITUDE',
        'X-LONGITUDE'
    ],


    'exposed_headers' => [],

    'max_age' => 60 * 60 * 24,

    'supports_credentials' => false,

];
