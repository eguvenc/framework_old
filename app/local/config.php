<?php

return array(

    /**
     * Friendly debugging feature. You can disable it in "production" environment.
     */
    'error' => [
        'debug' => true,
    ],
    /**
     * On / off logging
     */
    'log' => [
        'enabled' => true,
    ],
    /**
     * On / off http debugger web socket data activity
     */
    'http' => [
        'webhost'  => 'framework',
        'debugger' => [
            'enabled' => false,
            'socket' => 'ws://127.0.0.1:9000'
        ]
    ],
    /**
     * Locale
     */
    'locale' => [
        'timezone' => 'gmt',
        'charset'  => 'UTF-8',
        'date' => [
            'php_date_default_timezone' => 'Europe/London',
            'format' => 'H:i:s d:m:Y'
        ]
     ],
    /**
     * Layer
     */
    'layer' => [
        'cache' => false
    ],
    /**
     * Cookies
     */
    'cookie' => [ 
        'domain' => '',
        'path'   => '/',
        'secure' => false,
        'httpOnly' => false,
        'expire' => 604800,
        'prefix' => '',
    ],
    /**
     * Proxy Ips : Reverse Proxy IPs , If your server is behind a reverse proxy, you must whitelist the proxy IP
     *       addresses. Comma-delimited, e.g. '10.0.1.200,10.0.1.201'
     */
    'trusted' => [
        'ips' => '',
    ],
    /**
     * Sets your encryption key and protection settings.
     */
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

);