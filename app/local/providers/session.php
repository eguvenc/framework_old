<?php

return array(
    
    'params' => [

        'provider' => [
            'connection' => 'default'
        ],
        'storage' => [
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
    ],
    'methods' => [
        'setParameters' => [
            'registerSaveHandler' => '\Obullo\Session\SaveHandler\Cache',
            'setName' => '',
            'start' => '',
        ]
    ]
);