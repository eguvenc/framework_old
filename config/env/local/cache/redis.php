<?php

return array(

    'connections' => 
    [
        'default' => [              // Default connection always use serializer none
            'host' => '127.0.0.1',
            'port' => 6379,
            'options' => [
                'persistent' => false,
                'auth' => $c['env']['REDIS_AUTH'], // Connection password
                'timeout' => 30,
                'attempt' => 100,  // For persistent connections
                'serializer' => 'none',
                'database' => null,
                'prefix' => null,
            ]
        ],
        
        'second' => [               // Second connection always use a "serializer"
            'host' => '127.0.0.1',
            'port' => 6379,
            'options' => [
                'persistent' => false,
                'auth' => $c['env']['REDIS_AUTH'],
                'timeout' => 30,
                'attempt' => 100,
                'serializer' => 'php',
                'database' => null,
                'prefix' => null,
            ]
        ],

    ],

    'nodes' => [
        [
            'host' => '',
            'port' => 6379,
        ]
    ],

);