<?php

return array(

    'server' => array(
        'host'  => $c['env']['AMQP_HOST'],
        'user'  => $c['env']['AMQP_USERNAME'],
        'pass'  => $c['env']['AMQP_PASSWORD'],
        'port'  => 5672,
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
        'storage' => '\Obullo\Queue\Failed\Storage\Database',
        'provider' => array(
            'name' => 'database',       // Db provider which is defined in your "Provider" folder.
            'connection' => 'failed',     // Database key
        ),
        'table' => 'failures',
    ),
);

/* End of file queue.php */
/* Location: .app/config/env/local/queue.php */