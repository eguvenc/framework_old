<?php

return array(

    'connections' => 
    [
        'default' => [
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 1,
            'options' => [
                'persistent' => true,
                'timeout' => 30,    // seconds
                'attempt' => 100,
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

);