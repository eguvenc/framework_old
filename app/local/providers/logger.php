<?php

return array(
    
    'params' => [
    
        'default' => [
            'channel' => 'system',
        ],
        'priorities' => [
            'emergency' => 7,
            'alert'     => 6,
            'critical'  => 5,
            'error'     => 4,
            'warning'   => 3,
            'notice'    => 2,
            'info'      => 1,
            'debug'     => null,
        ],
        'format' => [
            'line' => '[%datetime%] %channel%.%level%: --> %message% %context% %extra%\n',
            'date' =>  'Y-m-d H:i:s',
        ],
        'queue' => [
            'job' => 'logger.1',
            'delay' => 0,
        ],
        'push' => [
            'handler' => 'Workers\Logger'  // Obullo\Log\Queue
        ]
    ],
    'methods' => [
        ['name' => 'registerFilter','argument' => ['priority', 'Obullo\Log\Filter\PriorityFilter']],
        ['name' => 'registerHandler', 'argument' => [5, 'file']],
        ['name' => 'registerHandler','argument' => [4, 'mongo']],
        ['name' => 'filter', 'argument' => ['priority@notIn', array()]],
        ['name' => 'setWriter','argument' => ['file']],
        ['name' => 'filter', 'argument' => ['priority@notIn', array()]],
    ]
);