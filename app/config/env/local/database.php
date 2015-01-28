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
            'hostname' => 'localhost',
            'username' => $c['env']['MYSQL_USERNAME.root'],
            'password' => $c['env']['MYSQL_PASSWORD.NULL'],
            'dsn'      => 'mysql:host=localhost;port=;dbname=demo_blog',
            'options'  => [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
            ]
        ),

        'failed' => array(
            'hostname' => 'localhost',
            'username' => $c['env']['MYSQL_USERNAME.root'],
            'password' => $c['env']['MYSQL_PASSWORD.NULL'],
            'dsn'      => 'mysql:host=localhost;port=;dbname=failed',
            'options'  => [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
            ]
        ),
    )

);

/* End of file database.php */
/* Location: .app/config/env/local/database.php */