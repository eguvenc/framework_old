<?php

return array(

    'default' => [
        'channel' => 'system',       // Default channel name should be general.
    ],
    
    'file' => [
        'path' => [
            'http'  => '/resources/data/logs/http.log',  // Http requests log path  ( Only for File Handler )
            'cli'   => '/resources/data/logs/cli.log',   // Cli log path  
            'ajax'  => '/resources/data/logs/ajax.log',  // Ajax log path
        ]
    ],

    'format' => [
        'line' => '[%datetime%] %channel%.%level%: --> %message% %context% %extra%\n',  // This format just for line based log drivers.
        'date' =>  'Y-m-d H:i:s',
    ],
    
    'app' => [
        'query' => [
            'log'=> true,   // If true "all" SQL Queries gets logged.
        ],
        'benchmark' => true,       // If true "all" Application Benchmarks gets logged.
    ],
);

/* End of file logger.php */
/* Location: .app/config/env.local/logger.php */
