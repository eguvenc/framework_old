<?php

return array(
    
    'params' => [
    
        /**
         * AMQP Settings
         * 
         * Exchange
         *     type : Exchange types : AMQP_EX_TYPE_DIRECT (direct) / AMQP_EX_TYPE_FANOUT (fanout) / AMQP_EX_TYPE_HEADER (header) / AMQP_EX_TYPE_TOPIC (topic),
         *     flag : Exchange flags : AMQP_DURABLE (durable), AMQP_PASSIVE (passive)
         */
        'amqp' => [

            'exchange' => [
                'type' => 'direct',
                'flag' => 'durable',
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