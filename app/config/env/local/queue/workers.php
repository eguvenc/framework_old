<?php

return array(

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

/* End of file workers.php */
/* Location: .app/config/env/local/queue/workers.php */