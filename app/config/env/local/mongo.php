<?php

return array(

    'connections' =>
    [
        'default' => [
            'server' => 'mongodb://'.$c['env']['MONGO_USERNAME'].':'.$c['env']['MONGO_PASSWORD'].'@'.$c['env']['MONGO_HOST'].':27017',
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