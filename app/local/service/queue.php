<?php

return array(
    
    'params' => [
    
        'amqp' => [

            'exchange' => [
                'type' => 'direct',  // fanout / header / topic
                'flag' => 'durable', // passive
            ],
            'connections' => 
            [
                'default' => [
                    'host'  => '127.0.0.1',
                    'port'  => 5672,
                    'username'  => 'root',
                    'password'  => '123456',
                    'vhost' => '/',
                ],
            ],
        ],
        'provider' => [
            'name' => 'amqp',
            'params' => [
                'connection' => 'default'
            ]
        ],
        'failedJob' => 
        [
            'enabled' => true,
            'storage' => '\Obullo\Queue\Failed\Storage\Database',
            'provider' => [
                'name' => 'database',
                'params' => [
                    'connection' => 'failed',
                ]
            ],
            'table' => 'failures',
        ],
    ]
);