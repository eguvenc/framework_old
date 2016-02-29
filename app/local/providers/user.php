<?php

return array(
    
    'params' => [

        'db.adapter' => 'Obullo\Authentication\Adapter\Database',
        'db.model' => 'Obullo\Authentication\Model\Database',
        'db.provider' => [
            'connection' => 'default'
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
            'key' => 'Auth',
            'storage' => 'Obullo\Authentication\Storage\Redis',
            'provider' => [
                'connection' => 'default'
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
        'password' => [
            'algo' => PASSWORD_BCRYPT,
            'cost' => 6
        ],
        'session' => [
            'regenerateSessionId' => true,
        ],
        'middleware' => [
            'unique.session' => false
        ]
    ]
);