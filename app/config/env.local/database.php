<?php

return array(
    
    'connections' => 
    [
        'default' => [
            'dsn'      => 'pdo_mysql:host=localhost;port=;dbname=test',
            'username' => 'root',
            'password' => $c['env']['MYSQL_PASSWORD.null'],
            'options'  => [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
            ]
        ],

        'failed' => [
            'dsn'      => 'pdo_mysql:host=localhost;port=;dbname=failed',
            'username' => 'root',
            'password' => $c['env']['MYSQL_PASSWORD.null'],
            'options'  => [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
            ]
        ],
    ]

);

/* End of file database.php */
/* Location: .app/config/env.local/database.php */