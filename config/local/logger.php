<?php

return array(

    /**
     * Default log channel
     */
    'default' => [
        'channel' => 'system',
    ],
    
    /**
     * Keep values null if you need native order.
     */
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

    /**
     * File handler log paths
     *
     * Path : 
     *     http : Http requests log data 
     *     cli  : Console requests log data 
     *     ajax : Ajax requests log data 
     *     worker : Queue worker log data
     */
    'file' => [
        'path' => [
            'http'  => '/resources/data/logs/http.log',
            'cli'   => '/resources/data/logs/cli.log',
            'ajax'  => '/resources/data/logs/ajax.log',
        ]
    ],

    /**
     * Log format for line based log drivers.
     */
    'format' => [
        'line' => '[%datetime%] %channel%.%level%: --> %message% %context% %extra%\n',
        'date' =>  'Y-m-d H:i:s',
    ],
    
    /**
     * Application query & benchmark logs
     *
     * Query : 
     *     log : If true "all" SQL Queries gets logged.
     *     
     * Benchmark :
     *     log : If true "all" Application Benchmarks gets logged.
     *
     * Worker
     *     log : If true all "php task queue listen" console process gets logged.
     */
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
);