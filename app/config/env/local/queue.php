<?php
/*
|--------------------------------------------------------------------------
| Queue Class Configuration
|--------------------------------------------------------------------------
| Configuration file
|
*/
return array(
    'server' => array(
        'host'  => '10.0.0.157',
        'port'  => 5672,
        'user'  => $_ENV['AMQP_USERNAME'],
        'pass'  => $_ENV['AMQP_PASSWORD'],
        'vhost' => '/',
        'debug' => true,
        'default' => array('queue' => 'default'),
        'exchange' => array(
            'type' => 'AMQP_EX_TYPE_DIRECT', // AMQP_EX_TYPE_DIRECT, AMQP_EX_TYPE_FANOUT, AMQP_EX_TYPE_HEADER or AMQP_EX_TYPE_TOPIC,
            'flag' => 'AMQP_DURABLE', // AMQP_PASSIVE
        ),
    ),
    'failed' => array(
        'storage' => 'Obullo\Queue\Failed\Storage\Database',
        'provider' => array(
            'name' => 'Db',     // "Db" provider which is defined in your "Provider" folder.
            'key' => 'q_jobs',  // Database name
        ),
        'table' => 'failures',
    ),
);

/* End of file queue.php */
/* Location: .app/env/local/queue.php */