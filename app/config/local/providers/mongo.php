<?php

return array(

    'params' => [

        'connections' =>
        [
            'default' => [
                'server' => 'mongodb://root:123456@localhost:27017',
                'options'  => ['connect' => true]
            ],
            'second' => [
                'server' => 'mongodb://test:123456@localhost:27017',
                'options'  => ['connect' => true]
            ]
        ],
    ]
);