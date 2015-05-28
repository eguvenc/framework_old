<?php

return array(

    'connections' =>
    [
        'default' => [
            'server' => 'mongodb://root:'.$c['env']['MONGO_PASSWORD'].'@localhost:27017',
            'options'  => ['connect' => true]
        ],
        'second' => [
            'server' => 'mongodb://test:123456@localhost:27017',
            'options'  => ['connect' => true]
        ]
    ],

);

/* End of file mongo.php */
/* Location: .app/config/env/local/mongo.php */