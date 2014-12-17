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
        'host'  => envget('AMQP_HOST'),
        'port'  => 5672,
        'user'  => envget('AMQP_USERNAME'),
        'pass'  => envget('AMQP_PASSWORD'),
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
            'name' => 'Db',         // "Db" provider which is defined in your "Provider" folder.
            'db' => 'failed_jobs',  // Database name
        ),
        'table' => 'failures',
    ),
);

/* End of file queue.php */
/* Location: .app/env/local/queue.php */