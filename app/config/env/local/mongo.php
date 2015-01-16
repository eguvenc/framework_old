<?php

return array(

    'default' => array(
        'database' => 'db',
    ),

    'key' => array(
        
        'db' => array(
            'host' => $c['env']['MONGO_HOST'],
            'username' => $c['env']['MONGO_USERNAME'],
            'password' => $c['env']['MONGO_PASSWORD'],
            'port' => '27017',
            ),
    ),
);

/* End of file mongo.php */
/* Location: .app/config/env/local/mongo.php */