<?php

return array(

    /**
     * Session save handler
     */
    'saveHandler' => '\Obullo\Session\SaveHandler\Cache',
    
    /**
     * Cache configuration
     *
     * Provider : 
     *     driver : Cache driver name
     *     connection : Connection name that is defined in your config/cache/$driver.php file, you can find it in 'connections' array.
     */
    'cache' => [
        'provider' => [
            'driver' => 'redis',
            'connection' => 'default'
        ]
    ],

    /**
     * Cache storage settings
     */
    'storage' => [
        'key' => 'sessions:', // Don't remove ":" colons. If your cache handler redis, it keeps keys in folders using colons.
        'lifetime' => 3600,   // Set storage lifetime 3600 = 1 hours.
    ],

    /**
     * Session cookie
     *
     * Expire   : if "0" session will expire automatically when the browser window is closed
     * name     : Session cookie name you want for the cookie
     * domain   : Set to .your-domain.com for site-wide cookies
     * path     : Typically will be a forward slash
     * secure   : When set to true, the cookie will only be set if a https:// connection exists.
     * httpOnly : When true the cookie will be made accessible only through the HTTP protocol
     * prefix   : Optionally you can set a prefix for your cookie
     */
    'cookie' => [
        'expire' => 0,
        'name'     => 'session',
        'domain'   => $c['env']['COOKIE_DOMAIN.null'],
        'path'     => '/',
        'secure'   => false,
        'httpOnly' => false, 
        'prefix'   => '', 
    ],
    
    /**
     * Session meta data
     *
     * Enabled : On / off session meta data feaure.
     * refresh : How many seconds between framework refreshing "Session" meta data Information"
     * matchIp : Whether to match the user's IP address when reading the session data
     * matchUserAgent : Whether to match the User Agent when reading the session data
     */
    'meta' => [
        'enabled' => false,
        'refresh'  => 200,
        'matchIp' => false,
        'matchUserAgent' => false
    ]

);