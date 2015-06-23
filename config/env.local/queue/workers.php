<?php

return array(

    'failed' => 
    [
        'enabled' => true,
        'storage' => '\Obullo\Queue\Failed\Storage\Database',
        'provider' => [
            'connection' => 'failed',   // connection name
        ],
        'table' => 'failures',
    ],
);

/* End of file workers.php */
/* Location: .config/env.local/queue/workers.php */