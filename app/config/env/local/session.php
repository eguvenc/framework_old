<?php

return array(

    'saveHandler' => '\Obullo\Session\SaveHandler\Cache',
    
    'cache' => [
        'provider' => [
            'driver' => 'redis',        // Cache provider configuration
            'connection' => 'default'
        ]
    ],

    'session' => [
        'key' => 'sessions:',      // Don't remove ":" colons. If your cache handler redis, it keeps keys in folders using colons.
        'lifetime' => 7200,        // The number of SECONDS you want the session to last. By default " 2 hours ". "0" is no expiration.
    ],

    'cookie' => [
        'lifetime' => 0,            // If lifetime  == 0  session will expire automatically when the browser window is closed
        'name'     => 'session',    // The name you want for the cookie
        'domain'   => $c['env']['COOKIE_DOMAIN.null'],             // Set to .your-domain.com for site-wide cookies
        'path'     => '/',                                         // Typically will be a forward slash
        'secure'   => false,                                       // When set to true, the cookie will only be set if a https:// connection exists.
        'httpOnly' => false,                                       // When true the cookie will be made accessible only through the HTTP protocol
        'prefix'   => '',                                          // Set a prefix to your cookie
    ],
    
    'meta' => [
        'enabled' => false,
        'refresh'  => 200,        // How many seconds between framework refreshing "Session" meta data Information"
        'matchIp' => false,       // Whether to match the user's IP address when reading the session data
        'matchUserAgent' => true  // Whether to match the User Agent when reading the session data
    ]

);

/* End of file session.php */
/* Location: .app/config/env/local/session.php */