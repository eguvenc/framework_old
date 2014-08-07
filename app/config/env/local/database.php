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
        'username' => 'root',
        'password' => '123456',
        'database' => 'demo_blog',
        'port'     => '',
        'charset'  => 'utf8',
        'dsn'      => '',
        'options'  => array()
    ),
    'queue_jobs' => array(
        'host' => 'localhost',
        'username' => 'root',
        'password' => '123456',
        'database' => 'queue_jobs',
        'port'     => '',
        'charset'  => 'utf8',
        'dsn'      => '',
        'options'  => array()
    ),
);

/* End of file database.php */
/* Location: .app/env/local/database.php */