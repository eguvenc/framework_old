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
        'username' => envget('DATABASE_USERNAME'),
        'password' => envget('DATABASE_PASSWORD'),
        'database' => 'test',
        'port'     => '',
        'charset'  => 'utf8',
        'autoinit' => array('charset' => true, 'bufferedQuery' => true),
        'dsn'      => '',
        'pdo'      => array(
            'options'  => array()
        ),
    ),
    'q_jobs' => array(
        'host' => 'localhost',
        'username' => envget('DATABASE_USERNAME'),
        'password' => envget('DATABASE_PASSWORD'),
        'database' => 'q_jobs',
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