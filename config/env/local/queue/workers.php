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