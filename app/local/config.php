<?php

return array(

    'log' => [
        'enabled' => true,
    ],
    'http' => [
        'webhost' => 'framework',
    ],
    'locale' => [
        'timezone' => 'gmt',
        'charset' => 'UTF-8',
        'date' => [
            'format' => 'H:i:s d:m:Y',
        ],
    ],
    'extra' => [
        'annotations' => false,
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
);
