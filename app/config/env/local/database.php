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
        'host' => 'localhost',
        'username' => envget('MYSQL_USERNAME'),
        'password' => envget('MYSQL_PASSWORD'),
        'database' => 'test',
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
        'username' => envget('MYSQL_USERNAME'),
        'password' => envget('MYSQL_PASSWORD'),
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