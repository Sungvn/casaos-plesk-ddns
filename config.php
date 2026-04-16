<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Minimal Plesk DDNS config
|--------------------------------------------------------------------------
|
| host => [
|   'domain' => zone managed by Plesk
|   'subdomain' => label inside that zone
| ]
|
| Examples:
|   homeserver.example.com => domain=example.com, subdomain=homeserver
|   example.com            => domain=example.com, subdomain=''
|
*/

return [
    // Shared secret used by the updater URL.
    // Example call:
    // https://ddns.example.com/update.php?key=your-secret-key&host=homeserver.example.com
    'secret' => 'your-secret-key',

    // Full path to the Plesk CLI
    'plesk_bin' => '/usr/sbin/plesk',

    // If your web server runs as a user that cannot execute Plesk CLI directly,
    // you may need to allow this script through sudo instead and set:
    // 'use_sudo' => true,
    'use_sudo' => true,

    // Optional: trust this proxy header for real client IP only if you are
    // definitely behind your own reverse proxy.
    'trusted_proxy_header' => null, // e.g. 'HTTP_X_FORWARDED_FOR'

    // Allowed hostnames this updater may change.
    'allowed_hosts' => [
        'homeserver.example.com' => [
            'domain' => 'example.com',
            'subdomain' => 'homeserver',
        ],

        // Example for root domain:
        // 'homeserver.example.com' => [
        //     'domain' => 'example.com',
        //     'subdomain' => '',
        // ],
    ],
];
