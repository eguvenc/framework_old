<?php

return array(
    
    'params' => [

        'connections' => 
        [
            'default' => [
                'dsn'      => 'pdo_mysql:host=localhost;port=;dbname=test',
                'username' => 'root',
                'password' => '123456',
                'options'  => [
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
                    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
                ]
            ],
            'failed' => [
                'dsn'      => 'pdo_mysql:host=localhost;port=;dbname=failed',
                'username' => 'root',
                'password' => '123456',
                'options'  => [
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
                    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
                ]
            ],
        ],

        'sql' => [
            'log' => true
        ]
    ]
);