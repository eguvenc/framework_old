<?php

return array(

    'log' => [
        'enabled' => true,
    ],
    'http' => [
        'webhost'  => 'framework',
        'debugger' => [
            'enabled' => false,
            'socket' => 'ws://127.0.0.1:9000'
        ]
    ],
    'locale' => [
        'timezone' => 'gmt',
        'charset'  => 'UTF-8',
        'date' => [
            'format' => 'H:i:s d:m:Y'
        ]
     ],
    'extra' => [
        'annotations' => true,
    ],
    'cookie' => [ 
        'domain' => '',
        'path'   => '/',
        'secure' => false,
        'httpOnly' => true,
        'expire' => 604800,
        'prefix' => '',
    ],
    'security' => [
        'encryption' => [
            'key' => 'write-your-secret-key',
        ]  
    ]
);