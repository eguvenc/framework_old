<?php

return array(
    
    'params' => [

        'cache.key' => 'Auth',
        'db.adapter'=> '\Obullo\Authentication\Adapter\Database',
        'db.model'  => '\Obullo\Authentication\Model\Pdo\User',
        'db.provider' => [
            'name' => 'database',
            'params' => [
                'connection' => 'default'
            ]
        ],
        'db.tablename' => 'users',
        'db.id' => 'id',
        'db.identifier' => 'username',
        'db.password' => 'password',
        'db.rememberToken' => 'remember_token',
        'db.select' => [
            'date',
        ],
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
        'login' => [
            'rememberMe'  => [
                'cookie' => [
                    'name' => '__rm',
                    'domain' => null,
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
            'unique.session' => false
        ]
    ]
);