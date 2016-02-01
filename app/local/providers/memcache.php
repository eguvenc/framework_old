<?php

return array(

    /**
     * Memcache Connections
     * 
     * Default
     *     host : Host address
     *     port : Port number
     *     weight : Integer weight
     * Options 
     *     persistent : On / off persistent connections
     *     timeout : Connection timeout in seconds
     *     attempt : Connection attempts
     */
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
    ]
);