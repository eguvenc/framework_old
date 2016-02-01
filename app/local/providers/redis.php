<?php

return array(

    /**
     * Redis Connections
     * 
     * Default
     *     host : Host address
     *     port : Port number
     *     weight : Integer weight
     * 
     * Options 
     *     persistent : On / off persistent connections
     *     auth : Auth password field
     *     timeout : Connection timeout in seconds
     *     attempt : Connection attempts
     *     serializer : None, php or igbinary
     *     database : Default database
     *     prefix : Default prefix
     *
     * Important : Default connection always use serializer "none", for second connection choose a serializer.
     */
    'params' => [

        'connections' => 
        [
            'default' => [
                'host' => '127.0.0.1',
                'port' => 6379,
                'options' => [
                    'persistent' => false,
                    'auth' => '',
                    'timeout' => 30,
                    'attempt' => 100,
                    'serializer' => 'none',
                    'database' => null,
                    'prefix' => null,
                ]
            ],
            
            'second' => [
                'host' => '127.0.0.1',
                'port' => 6379,
                'options' => [
                    'persistent' => false,
                    'auth' => '',
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
    ]
);