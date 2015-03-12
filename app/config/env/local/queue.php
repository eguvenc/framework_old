<?php

return array(

    'default' => array(
        'queueName' => 'default'  // default queue name
    ),
    'exchange' => array(
        'type' => 'AMQP_EX_TYPE_DIRECT', // AMQP_EX_TYPE_DIRECT, AMQP_EX_TYPE_FANOUT, AMQP_EX_TYPE_HEADER or AMQP_EX_TYPE_TOPIC,
        'flag' => 'AMQP_DURABLE',        // AMQP_PASSIVE
    ),
    'debug' => true,
    
    'AMQP' => array(

        'connections' => array(
            'default' => array(
                'host'  => $c['env']['AMQP_HOST'],
                'port'  => 5672,
                'username'  => $c['env']['AMQP_USERNAME'],
                'password'  => $c['env']['AMQP_PASSWORD'],
                'vhost' => '/',
            )
        ),
    ),

    'failed' => array(
        'enabled' => false,
        'storage' => '\Obullo\Queue\Failed\Storage\Database',
        'provider' => array(
            'name' => 'database',       // Set database service provider settings
            'connection' => 'failed',   // connection name
        ),
        'table' => 'failures',
    ),
);

/* End of file queue.php */
/* Location: .app/config/env/local/queue.php */