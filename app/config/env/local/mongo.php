<?php

return array(

    'default' => array(
        'connection'   => 'default',
        'database' => 'db',
    ),

    'connections' => array(

        'default' => array(
            'server' => 'mongodb://'.$c['env']['MONGO_USERNAME'].':'.$c['env']['MONGO_PASSWORD'].'@'.$c['env']['MONGO_HOST'].':27017',
            'options'  => array('connect' => true)
        )
        ,
        'second' => array(
            'server' => 'mongodb://test:123456@localhost:27017',
            'options'  => array('connect' => true)
        )

    ),
);

/* End of file mongo.php */
/* Location: .app/config/env/local/mongo.php */