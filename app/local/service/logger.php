<?php

return array(
    
    'params' => [
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