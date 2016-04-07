<?php

return array(
    
    'params' => [

        'provider' => [
            'connection' => 'default'
        ],
        'handler' => 'Obullo\Session\SaveHandler\Cache',
        'storage' => [
            'driver' => 'redis',  // apc, memcached, memcache, file
            'key' => 'sessions:',
            'lifetime' => 3600,
        ],
        'cookie' => [
            'expire' => 0,
            'name'     => 'session',
            'domain'   => '',
            'path'     => '/',
            'secure'   => false,
            'httpOnly' => false, 
            'prefix'   => '', 
        ],
    ]
);