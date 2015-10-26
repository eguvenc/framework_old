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
        'app' => [
            'query' => [
                'log'=> true,
            ],
            'benchmark' => [
                'log' => true,
            ],
            'worker' => [
                'log' => false,
            ]
        ],
        'queue' => [
            'enabled' => false,
            'job' => 'logger.1',
            'delay' => 0,
        ]
    ],
    'methods' => [
        ['registerFilter' => ['priority', 'Obullo\Log\Filter\PriorityFilter']],
        ['registerHandler' => [5, 'file']],
        ['registerHandler' => [4, 'mongo']],
        ['filter' => ['priority@notIn', array(LOG_DEBUG)]],
        ['registerHandler' => [3, 'email']],
        ['filter' => ['priority@notIn', array(LOG_DEBUG)]],
        ['setWriter' => ['file']],
        ['filter' => ['priority@notIn', array()]],
    ]
);