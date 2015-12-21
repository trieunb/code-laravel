<?php

return [
    'IOSApp'      => [
        'environment' => env('APN_ENV'),
        'certificate' => env('APN_CERT_PATH'),
        'passPhrase'  => '',
        'service'     => 'apns'
    ],
    'AndroidApp'  => [
        'environment' => 'production',
        'apiKey'      => env('GCM_API_KEY'),
        'service'     => 'gcm'
    ]
];
