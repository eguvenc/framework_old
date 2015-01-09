<?php

return array(

    'adapter' => '\Obullo\Authentication\Adapter\Database',  // Adapter

    'user' => array(
        'provider' => '\Obullo\Authentication\UserProvider', // User provider, you can replace it with your own.
    ),

    'memory' => array(              // Keeps user identity data in your cache driver.
        'key' => 'Auth',            // Auth key should be replace with your projectname to prevent collisions
        'storage' => '\Obullo\Authentication\Storage\Redis',   // Storage driver uses cache package
        'block' => array(
            'permanent' => array(
                'lifetime' => 86400,  // 24 hours
            ),
            'temporary'  => array(
                'lifetime' => 300    // 5 minutes is default temporary login lifetime.
            )
        )
    ),
    'security' => array(
        'cookie' => array(
            'name' => '__token',        // Cookie name
            'refresh' => 60,            // Every 1 minutes do the cookie validation
            'userAgentMatch' => false,  // Whether to match user agent when reading token
            'path' => '/',
            'secure' => false,
            'httpOnly' => false,
            'prefix' => '',
            'expire' => 86400   // 24 hours
        ),
        'passwordNeedsRehash' => array(
            'cost' => 6               // It depends of your server http://php.net/manual/en/function.password-hash.php
        ),                            // Set 6 for best performance and less security, set between 8 - 12  for strong security if your hardware strong..
    ),
    'login' => array(
        'rememberMe'  => array(
            'cookie' => array(
                'name' => '__rm',
                'path' => '/',
                'secure' => false,
                'httpOnly' => false,
                'prefix' => '',
                'expire' => 6 * 30 * 24 * 3600,  // Default " 6 Months ".
            )
        ),
        'session' => array(
            'regenerateSessionId' => true,               // Regenerate session id upon new logins.
            'deleteOldSessionAfterRegenerate' => false,  // Destroy old session data after regenerate the new session id upon new logins
        )
    ),
    'activity' => array(
        'uniqueSession' => false,  // If unique session enabled application terminates all other active sessions.
    )
);

/* End of file auth.php */
/* Location: .app/config/auth.php */
