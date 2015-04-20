<?php

return array(
    
    'cache' => [
        'storage' => '\Obullo\Authentication\Storage\Redis',  // Storage can be a Cache package or custom database like Redis.
        'provider' => [
            'driver' => 'redis',                              // If storage Not Cache provider['driver'] and storage values must be same.
            'connection' => 'second'
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
                'httpOnly' => false,
                'prefix' => '',
                'expire' => 6 * 30 * 24 * 3600,  // Default " 6 Months ".
            ]
        ],
    ],
    'session' => [
        'regenerateSessionId' => true,  // Regenerate session id upon new logins.
        'unique' => false,              // If unique session enabled application terminates all other active sessions.
    ]
);

/* End of file auth.php */
/* Location: .app/config/auth.php */