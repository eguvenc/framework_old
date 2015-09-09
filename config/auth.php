<?php

return array(

    /*
    | -------------------------------------------------------------------
    | Auth
    | -------------------------------------------------------------------
    | Storage : Auth storage path
    | provider : 
    |     driver : Cache provider name
    |     connection :  Connection name
    | block :
    |     permanent : Login query cache block
    |         lifetime : Fully authorized identity expire time; ( 3600 ) 1 hour is default.
    |     temporary : Temporary identity cache block
    |         lifetime : Unverified identity expire time; ( 300 ) 5 minutes is default.
    |
    */
    'cache' => [
    
        'storage' => '\Obullo\Authentication\Storage\Redis',
        'provider' => [
            'params' => [
                'driver' => 'redis',
                'connection' => 'default'
            ]
        ],
        'block' => [
            'permanent' => [
                'lifetime' => 3600,
            ],
            'temporary'  => [
                'lifetime' => 300
            ]
        ]
    ],

    /**
     * Login functionality
     *
     * Split : Allows multiple login tables to split. Forexample if you have two , default tablename is
     *                configured in your user service.
     * RememberMe : 
     *     cookie : 
     *         name   : Recaller cookie name
     *         domain : Set to .your-domain.com for site-wide cookies
     *         expire : It should be long period default is " 6 Months ".
     */
    'login' => [
        'rememberMe'  => [
            'cookie' => [
                'name' => '__rm',
                'domain' => $c['var']['COOKIE_DOMAIN.null'],
                'path' => '/',
                'secure' => false,
                'httpOnly' => true,
                'prefix' => '',
                'expire' => 6 * 30 * 24 * 3600,
            ]
        ],
    ],
    
    /**
     * Security
     *
     * PasswordNeedsRehash :
     *     cost : It depends of your server http://php.net/manual/en/function.password-hash.php 
     *            Set 6 for best performance but less security, if your "hardware" strong set between 8 - 12 for strong security.
     */
    'security' => [
        'passwordNeedsRehash' => [
            'cost' => 6
        ],
    ],

    /**
     * Session
     *
     * RegenerateId : Whether to regenerate session id upon new logins.
     */
    'session' => [
        'regenerateSessionId' => true,
    ],

    /**
     * Auth middlewares
     *
     * UniqueLogin : If this is true all other opened session in other devices will be logged out except the current session.
     */
    'middleware' => [
        'unique.login' => false
    ]

);