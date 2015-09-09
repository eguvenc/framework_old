<?php

return array(

    'connections' =>
    [
        'default' => [
            'server' => 'mongodb://root:'.$c['var']['MONGO_PASSWORD'].'@localhost:27017',
            'options'  => ['connect' => true]
        ],
        'second' => [
            'server' => 'mongodb://test:123456@localhost:27017',
            'options'  => ['connect' => true]
        ]
    ],

);