<?php

return array(
    
    'default' => array(
        'connection' => 'default',
    ),

    'handlers' => array(
        'mysql' => '\\Obullo\Database\Pdo\Handler\Mysql',
        'pgsql' => '\\Obullo\Database\Pdo\Handler\Pgsql',
        'yourhandler' => '\\Obullo\Database\Pdo\Handler\YourHandler',  // create your own using Cache/Handler/HandlerInterface.php
    ),

    'connections' => array(

        'default' => array(
            'driver'   => 'mysql',
            'hostname' => 'localhost',
            'username' => $c['env']['MYSQL_USERNAME.root'],
            'password' => $c['env']['MYSQL_PASSWORD.NULL'],
            'database' => 'test',
            'port'     => '',
            'charset'  => 'utf8',
            'dsn'      => '',
            'pdo'      => array(
                'options'  => [
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
                    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
                ]
            ),
        ),

        'failed' => array(
            'driver'   => 'mysql',
            'hostname' => 'localhost',
            'username' => $c['env']['MYSQL_USERNAME.REQUIRED'],
            'password' => $c['env']['MYSQL_PASSWORD.NULL'],
            'database' => 'failed',
            'port'     => '',
            'charset'  => 'utf8',
            'dsn'      => '',
            'pdo'      => array(
                'options'  => [
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
                    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
                ]
            ),
        ),
    )

);

/* End of file database.php */
/* Location: .app/config/env/local/database.php */