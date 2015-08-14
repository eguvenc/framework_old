<?php

return array(

    /**
     * Memcached Connections
     * 
     * Default
     *     host : Host address
     *     port : Port number
     *     weight : Integer weight
     *   
     * Options 
     *     persistent : On / off persistent connections
     *     pool : http://php.net/manual/en/memcached.construct.php
     *     timeout : Connection timeout in seconds
     *     attempt : Connection attempts
     *     serializer : Php, json or igbinary
     *     prefix : Default prefix
     */
    'connections' => 
    [
        'default' => [
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 1,
            'options' => [
                'persistent' => false,
                'pool' => 'connection_pool',
                'timeout' => 30,
                'attempt' => 100,
                'serializer' => 'php',
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