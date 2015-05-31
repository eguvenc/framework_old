<?php

return array(

    'saveHandler' => '\Obullo\Session\SaveHandler\Cache',
    
    'cache' => [
        'provider' => [
            'driver' => 'redis',        // Cache provider configuration
            'connection' => 'default'
        ]
    ],

    'storage' => [
        'key' => 'sessions:', // Don't remove ":" colons. If your cache handler redis, it keeps keys in folders using colons.
        'lifetime' => 3600,   // Set storage lifetime 3600 = 1 hours.
    ],

    'cookie' => [             // Session http cookie parameters
        'expire' => 0,        // if "0" session will expire automatically when the browser window is closed
        'name'     => 'session',                                   // The name you want for the cookie
        'domain'   => $c['env']['COOKIE_DOMAIN.null'],             // Set to .your-domain.com for site-wide cookies
        'path'     => '/',                                         // Typically will be a forward slash
        'secure'   => false,                                       // When set to true, the cookie will only be set if a https:// connection exists.
        'httpOnly' => false,                                       // When true the cookie will be made accessible only through the HTTP protocol
        'prefix'   => '',                                          // Set a prefix to your cookie
    ],
    
    'meta' => [
        'enabled' => false,
        'refresh'  => 200,         // How many seconds between framework refreshing "Session" meta data Information"
        'matchIp' => false,        // Whether to match the user's IP address when reading the session data
        'matchUserAgent' => false  // Whether to match the User Agent when reading the session data
    ]

);

/* End of file session.php */
/* Location: .app/config/env.local/session.php */