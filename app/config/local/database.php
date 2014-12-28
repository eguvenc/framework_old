<?php

return array(
    
    'default' => array(
        'provider' => 'mysql',
    ),

    'handlers' => array(
        'mysql' => '\\Obullo\Database\Pdo\Handler\Mysql',
        'pgsql' => '\\Obullo\Database\Pdo\Handler\Pgsql',
        'yourhandler' => '\\Obullo\Database\Pdo\Handler\YourHandler',  // You can create your own handler using Cache/Handler/HandlerInterface.php
    ),

    'key' => array(

        'db' => array(
            'host' => 'localhost',
            'username' => env('MYSQL_USERNAME'),
            'password' => env('MYSQL_PASSWORD', '', false),
            'database' => 'test',
            'port'     => '',
            'charset'  => 'utf8',
            'autoinit' => array('charset' => true, 'bufferedQuery' => true),
            'dsn'      => '',
            'pdo'      => array(
                'options'  => array()
            ),
        ),

        'failed' => array(
            'host' => 'localhost',
            'username' => env('MYSQL_USERNAME'),
            'password' => env('MYSQL_PASSWORD', '', false),
            'database' => 'failed_jobs',
            'port'     => '',
            'charset'  => 'utf8',
            'autoinit' => array('charset' => true, 'bufferedQuery' => true),
            'dsn'      => '',
            'pdo'      => array(
                'options'  => array()
            ),
        ),
    )

);

/* End of file database.php */
/* Location: .app/config/local/database.php */