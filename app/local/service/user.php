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
        ]
    ]
);