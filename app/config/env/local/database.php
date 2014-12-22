<?php
/*
|--------------------------------------------------------------------------
| Database
|--------------------------------------------------------------------------
| Configuration file
|
*/
return array(
    
    'db' => array(
        'host'     => '10.0.0.161',
        'username' => 'root',
        'password' => '123456',
        'database' => 'betforyousystem',
        // 'database' => 'test',
        'port'     => '',
        'charset'  => 'utf8',
        'autoinit' => array('charset' => true, 'bufferedQuery' => true),
        'dsn'      => '',
        'pdo'      => array(
            'options'  => array()
        ),
    ),

    'failed_jobs' => array(
        'host' => 'localhost',
        'username' => env('MYSQL_USERNAME'),
        'password' => env('MYSQL_PASSWORD'),
        'database' => 'failed_jobs',
        'port'     => '',
        'charset'  => 'utf8',
        'autoinit' => array('charset' => true, 'bufferedQuery' => true),
        'dsn'      => '',
        'pdo'      => array(
            'options'  => array()
        ),
    ),
);

/* End of file database.php */
/* Location: .app/env/local/database.php */