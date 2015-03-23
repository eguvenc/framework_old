<?php

return array(

    'exchange' => [
        'type' => 'AMQP_EX_TYPE_DIRECT', // AMQP_EX_TYPE_DIRECT, AMQP_EX_TYPE_FANOUT, AMQP_EX_TYPE_HEADER or AMQP_EX_TYPE_TOPIC,
        'flag' => 'AMQP_DURABLE',        // AMQP_PASSIVE
    ],
    
    'connections' => 
    [
        'default' => [
            'host'  => $c['env']['AMQP_HOST'],
            'port'  => 5672,
            'username'  => $c['env']['AMQP_USERNAME'],
            'password'  => $c['env']['AMQP_PASSWORD'],
            'vhost' => '/',
        ]
    ],
);

/* End of file queue.php */
/* Location: .app/config/env/local/queue/amqp.php */