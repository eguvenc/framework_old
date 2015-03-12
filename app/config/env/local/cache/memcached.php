<?php

return array(

    'connections' => array(

        'default' => array(
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 1,  
            'options' => array(
                'persistent' => [
                    'connection' => false,
                    'pool' => 'connection_pool'     // http://php.net/manual/en/memcached.construct.php
                ],
                'serializer' => 'php',    // php, json, igbinary
                'prefix' => null
            )
        )
    ),

    'nodes' => array(
        [
            'host' => '',
            'port' => '11211',
            'weight' => 1,           // The weight parameter effects the consistent hashing 
                                     // used to determine which server to read/write keys from.
        ]
    )

);

/* End of file memcached.php */
/* Location: .app/config/env/local/cache/memcached.php */