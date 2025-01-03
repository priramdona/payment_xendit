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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'xendit' => [
        // 'key' => 'xnd_production_uuzC6hN2NZsUFbNH4y4E5FmYtFOp4DR420eJY3zyiBBpAj7CylHdPyeDcgv9tp7',
        // 'public_key' => 'xnd_public_production_D7aFSh8NlkhwjDJqZH73vF1XERlVkus4x0jRhjeKPmaQLIgvJbOv5Uuc7axK2k',
        // 'key' => "xnd_development_kUV1T0HvdsGIZjjCB44UAZbf3y0uWdsiljJj8waKePO6Be3OYFsushKpNJHtujZB", //test munggi
        // 'public_key' => 'xnd_public_development_MH_989vykBMlxR6b45ZcoljKGwRxSQS7XxVzR38aw8bJBtcF5Mm79PuP0LBqmh5'//test munggi
        'key' => "xnd_development_YyGYTQMsAiGf3ca0eRu1fwQ01vYb5sc8sqdVyTGpCfTzSxWgf09TUDPtLYSzbse5", //test primaraharjamulia
        'public_key' => 'xnd_public_development_lAsH_QDuLpMrFfhVeQicsHSnwTo6ppnIlyzo98r8HGiucEmn6Sxl23MIZPgmZAx'//test primaraharjamulia

    ],

];
