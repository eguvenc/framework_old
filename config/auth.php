<?php

return array(
    
    /*
    | -------------------------------------------------------------------
    | Auth - ( User Service )
    | -------------------------------------------------------------------
    | This file contains auth configuration. It is used by Authentication/ package to manage
    | auth service behaviours.
    |
    | Note : This configuration will be merged with configuration parameter in Authentication/AuthServiceProvider class.
    |
    */
    'cache' => [
    
        'storage' => '\Obullo\Authentication\Storage\Null',  // Storage can be a Cache package or custom database like Redis.
        'provider' => [
            'driver' => 'null',                              // If storage Not Cache provider['driver'] and storage values must be same.
            'connection' => 'null'
        ],
        'block' => [
            'permanent' => [
                'lifetime' => 3600,  // 1 hour is default permanent identity cache time for database queries.
            ],
            'temporary'  => [
                'lifetime' => 300    // 5 minutes is default temporary identity lifetime.
            ]
        ]
    ],

    'tables' => [
        'users' => [
            'db.id' => 'id',
            'db.identifier' => 'username',
            'db.password' => 'password',
            'db.rememberToken' => 'remember_token',
        ]
    ],

    'security' => [
        'passwordNeedsRehash' => [
            'cost' => 6               // It depends of your server http://php.net/manual/en/function.password-hash.php
        ],                            // Set 6 for best performance and less security, set between 8 - 12 for strong security if your "hardware" strong.
    ],

    'login' => [
        'rememberMe'  => [
            'cookie' => [
                'name' => '__rm',
                'domain' => $c['env']['COOKIE_DOMAIN.null'],  // Set to .your-domain.com for site-wide cookies
                'path' => '/',
                'secure' => false,
                'httpOnly' => true,
                'prefix' => '',
                'expire' => 6 * 30 * 24 * 3600,  // Default " 6 Months ".
            ]
        ],
    ],

    'session' => [
        'regenerateSessionId' => true, // Regenerate session id upon new logins. 
    ],

    'middleware' => [
        'uniqueLogin' => false         // If unique login enabled application terminates all other active sessions.
    ]

);

/* End of file auth.php */
/* Location: .config/auth.php */