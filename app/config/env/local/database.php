<?php

return array(
    
    'default' => array(
        'connection' => 'default',
    ),

    'connections' => array(

        'default' => array(
            'dsn'      => 'mysql:host=localhost;port=;dbname=test',
            'username' => $c['env']['MYSQL_USERNAME.root'],
            'password' => $c['env']['MYSQL_PASSWORD.NULL'],
            'options'  => [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
            ]
        ),

        'failed' => array(
            'dsn'      => 'mysql:host=localhost;port=;dbname=failed',
            'username' => $c['env']['MYSQL_USERNAME.root'],
            'password' => $c['env']['MYSQL_PASSWORD.NULL'],
            'options'  => [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
            ]
        ),
    )

);

/* End of file database.php */
/* Location: .app/config/env/local/database.php */