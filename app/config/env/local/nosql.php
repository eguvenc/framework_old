<?php
/*
|--------------------------------------------------------------------------
| NoSQL Databases
|--------------------------------------------------------------------------
| Configuration file
|
*/
return array(
    'mongo' => array(
        'db' => array(
            'host' => 'localhost',
            'username' => 'root',
            'password' => '123456',
            'port' => '27017',
            ),
        'yourSecondDatabaseName' => array(
            'host' => '',
            'username' => '',
            'password' => '',
            'port' => '',
        )
    // 'provider' => array()  Another noSQL database provider
    ),
);

/* End of file nosql.php */
/* Location: .app/env/local/nosql.php */