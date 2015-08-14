<?php

return array(

    /**
     * AMQP Settings
     * 
     * Exchange
     *     type : Exchange types : AMQP_EX_TYPE_DIRECT / AMQP_EX_TYPE_FANOUT / AMQP_EX_TYPE_HEADER / AMQP_EX_TYPE_TOPIC,
     *     flag : Exchange flags : AMQP_DURABLE, AMQP_PASSIVE
     *
     * @link http://php.net/manual/pl/amqpexchange.setflags.php
     */
    'amqp' => [

        'exchange' => [
            'type' => 'AMQP_EX_TYPE_DIRECT',
            'flag' => 'AMQP_DURABLE',
        ],
        
        'connections' => 
        [
            'default' => [
                'host'  => '127.0.0.1',
                'port'  => 5672,
                'username'  => 'root',
                'password'  => $c['env']['AMQP_PASSWORD'],
                'vhost' => '/',
            ]
        ],
    ],

    /**
     * Failed job configuration
     */
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
    ]

);