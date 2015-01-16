<?php

return array(
    
    'default' => array(
        'provider' => 'mysql',
        'database' => 'db',
    ),

    'handlers' => array(
        'mysql' => '\\Obullo\Database\Pdo\Handler\Mysql',
        'pgsql' => '\\Obullo\Database\Pdo\Handler\Pgsql',
        'yourhandler' => '\\Obullo\Database\Pdo\Handler\YourHandler',  // create your own using Cache/Handler/HandlerInterface.php
    ),

    'key' => array(

        'db' => 
            array(
                'host' => 'localhost',
                'username' => $c['env']['MYSQL_USERNAME.root'],
                'password' => $c['env']['MYSQL_PASSWORD.NULL'],
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
            'username' => $c['env']['MYSQL_USERNAME.REQUIRED'],
            'password' => $c['env']['MYSQL_PASSWORD.NULL'],
            'database' => 'failed',
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
/* Location: .app/config/env/local/database.php */