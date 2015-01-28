<?php

return array(

    'default' => array(
        'handler' => 'cache',
    ),

    'handlers' => array(
        'cache' => '\\Obullo\Session\Handler\Cache',
        'mongo' => '\\Obullo\Session\Handler\Mongo',
        'yourhandler' => '\\Session\Handler\YourHandler' // You can create your own handler using Session/Handler/HandlerInterface.php
    ),

    'cache' => array(
        'storage' =>'redis',
    ),

    'session' => array(
        'key' => 'o2_sessions:',  // Don't remove ":" colons. Your cache handler keeps keys in folders using colons.
        'lifetime' => 7200,       // The number of SECONDS you want the session to last. By default " 2 hours ". "0" is no expiration.
        'expireOnClose' => true,  // Whether to cause the session to expire automatically when the browser window is closed
        'timeToUpdate'  => 1,     // How many seconds between framework refreshing "Session" meta data Information"
    ),

    'cookie' => array(
        'name'     => $c['env']['SESSION_COOKIE_NAME.session'],    // The name you want for the cookie
        'domain'   => $c['env']['SESSION_COOKIE_DOMAIN.NULL'],     // Set to .your-domain.com for site-wide cookies
        'path'     => $c['env']['SESSION_COOKIE_PATH./'],          // Typically will be a forward slash
        'secure'   => $c['env']['SESSION_COOKIE_SECURE'],          // When set to true, the cookie will only be set if a https:// connection exists.
        'httpOnly' => $c['env']['SESSION_COOKIE_HTTP_ONLY'],       // When true the cookie will be made accessible only through the HTTP protocol
        'prefix'   => '',                                          // Set a prefix to your cookie
    ),
    
    'meta' => array(
        'enabled' => true,
        'matchIp' => false,       // Whether to match the user's IP address when reading the session data
        'matchUserAgent' => true  // Whether to match the User Agent when reading the session data
    )
);

/* End of file session.php */
/* Location: .app/config/env/local/session.php */