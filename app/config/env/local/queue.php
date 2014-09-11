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
        'user'  => 'root',
        'pass'  => '123456',
        'vhost' => '/',
        'debug' => true,
        'defaultQueueName' => 'defaultQueue',
        'exchangeType' => 'AMQP_EX_TYPE_DIRECT', // AMQP_EX_TYPE_DIRECT, AMQP_EX_TYPE_FANOUT, AMQP_EX_TYPE_HEADER or AMQP_EX_TYPE_TOPIC,
        'exchangeFlag' => 'AMQP_DURABLE', // AMQP_PASSIVE
    ),
    'failed' => array(
        'storage' => 'Obullo\Queue\Failed\Storage\Database',
        'provider' => array(
            'name' => 'Database',   // "Database" provider which is defined in your "Provider" folder.
            'key' => 'q_jobs',
        ),
        'table' => 'failures',
        // 'emergency' => 'Obullo\Emergency\Email' // When the job fails failedJob class will push data to your emergency handler.
    ),
);

/* End of file queue.php */
/* Location: .app/env/local/queue.php */