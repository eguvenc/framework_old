<?php

return array(

    /**
     * Important : Default connection always use serializer "none".
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
        ]
    ]
);