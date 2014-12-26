<?php

return array(

    'server' => array(
        'host'  => env('AMQP_HOST'),
        'port'  => 5672,
        'user'  => env('AMQP_USERNAME'),
        'pass'  => env('AMQP_PASSWORD'),
        'vhost' => '/',
        'debug' => true,
        'default' => array('queue' => 'default'),
        'exchange' => array(
            'type' => 'AMQP_EX_TYPE_DIRECT', // AMQP_EX_TYPE_DIRECT, AMQP_EX_TYPE_FANOUT, AMQP_EX_TYPE_HEADER or AMQP_EX_TYPE_TOPIC,
            'flag' => 'AMQP_DURABLE', // AMQP_PASSIVE
        ),
    ),
    
    'failed' => array(
        'enabled' => false,
        'storage' => 'Obullo\Queue\Failed\Storage\Database',
        'provider' => array(
            'name' => 'Db',       // Db provider which is defined in your "Provider" folder.
            'db' => 'failed',     // Database key
            'provider' => 'mysql' // Database driver
        ),
        'table' => 'failures',
    ),
);

/* End of file queue.php */
/* Location: .app/env/local/queue.php */