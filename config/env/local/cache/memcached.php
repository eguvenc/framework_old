<?php

return array(

    'connections' => 
    [
        'default' => [
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 1,
            'options' => [
                'persistent' => false,
                'pool' => 'connection_pool',   // http://php.net/manual/en/memcached.construct.php
                'timeout' => 30,               // seconds
                'attempt' => 100,
                'serializer' => 'php',    // php, json, igbinary
                'prefix' => null
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

/* End of file memcached.php */
/* Location: .config/env/local/cache/memcached.php */