<?php

return array(

    'log' => [
        'enabled' => true,
    ],
    'locale' => [
        'timezone' => 'gmt',
        'charset' => 'UTF-8',
        'date' => [
            'format' => 'H:i:s d:m:Y',
        ],
    ],
    'cookie' => [
        'domain' => '',
        'path' => '/',
        'secure' => false,
        'httpOnly' => true,
        'expire' => 604800,
        'prefix' => '',
    ],
    'security' => [
        'encryption' => [
            'key' => 'write-your-secret-key',
        ],
    ],
    'extra' => [
        'debugger' => true,
        'annotations' => false,
    ],
);
