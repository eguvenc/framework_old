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
    'controller' => [
        'annotation' => false,
    ],
    'cookie' => [ 
        'domain' => '',
        'path'   => '/',
        'secure' => false,
        'httpOnly' => false,
        'expire' => 604800,
        'prefix' => '',
    ],
    'security' => [
        'encryption' => [
            'key' => 'write-your-secret-key',
        ],
        'csrf' => [
            'protection' => true,
            'token' => [
                'name' => 'csrf_token',
                'refresh' => 30,
            ],    
         ],     
    ],
    /**
     * Proxy Ips : Reverse Proxy IPs , If your server is behind a reverse proxy, you must whitelist the proxy IP
     *       addresses. Comma-delimited, e.g. '10.0.1.200,10.0.1.201'
     */
    'trusted' => [
        'ips' => '',
    ],
);