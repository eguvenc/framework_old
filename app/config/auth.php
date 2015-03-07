<?php

return array(
    
    'cache' => array(
        'storage' => '\Obullo\Authentication\Storage\Redis',  // Storage can be a Cache package or custom database like Redis.
        'provider' => array(
            'driver' => 'redis',                         // If storage Not Cache provider['driver'] and storage values must be same.
            'options' => array('serializer' => 'php')   // json, igbinary
        ),
        'block' => array(
            'permanent' => array(
                'lifetime' => 86400, // 24 hours default, it should be long period ( this is identity cache )
            ),
            'temporary'  => array(
                'lifetime' => 300    // 5 minutes is default temporary login lifetime.
            )
        )
    ),
    'security' => array(
        'cookie' => array(
            'name' => '__token',    // Cookie name
            'refresh' => 10,        // Every 1 minutes do the cookie validation
            'path' => '/',
            'secure' => false,
            'httpOnly' => false,
            'expire' => 0,         // If value == 0 session will be expire automatically when the browser window is closed.
            'prefix' => '',

        ),
        'passwordNeedsRehash' => array(
            'cost' => 6               // It depends of your server http://php.net/manual/en/function.password-hash.php
        ),                            // Set 6 for best performance and less security, set between 8 - 12  for strong security if your hardware strong..
    ),
    'login' => array(
        'route' => '/examples/login',
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
            'regenerateSessionId' => true,  // Regenerate session id upon new logins.
        )
    ),
    'activity' => array(
        'uniqueLogin' => false,  // If unique login enabled application terminates all other active sessions.
    )
);

/* End of file auth.php */
/* Location: .app/config/auth.php */