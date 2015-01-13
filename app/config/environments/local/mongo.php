<?php

return array(

    'default' => array(
        'database' => 'db',
    ),

    'key' => array(
        
        'db' => array(
            'host' => env('MONGO_HOST'),
            'username' => env('MONGO_USERNAME'),
            'password' => env('MONGO_PASSWORD'),
            'port' => '27017',
            ),
    ),
);

/* End of file mongo.php */
/* Location: .app/config/local/mongo.php */