<?php

return [
    'web_service_url' => env('APPLE_WEB_SERVICE_URL', 'https://copperchimney.knconsulting.uk/api/apple-wallet/v1'),


        'apn' => [
        'key_id' => env('APN_KEY_ID'),
        'team_id' => env('APN_TEAM_ID'),
        'private_key_path' => env('APN_PRIVATE_KEY_PATH'),
        'pass_type_identifier' => env('APN_BUNDLE_ID'),
        'production' => env('APP_ENV') === 'production',
    ],
];
