<?php

return array(

    'params' => [

        'connections' => 
        [
            'default' => [
                'host' => '127.0.0.1',
                'port' => 11211,
                'weight' => 1,
                'options' => [
                    'persistent' => true,
                    'timeout' => 30,
                    'attempt' => 100, // connection attempts
                ]
            ]
        ],

        'nodes' => [
            [
                'host' => '',
                'port' => '11211',
                'weight' => 1
            ]
        ]
    ]
);