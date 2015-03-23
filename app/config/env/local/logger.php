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
    
    'extra' => [
        'queries'   => true,       // If true "all" SQL Queries gets logged.
        'benchmark' => true,       // If true "all" Application Benchmarks gets logged.
    ],

    'queue' => [
        'service' => 'queue',     // Queue service name its located in app/Service folder.
        'channel' => 'Log',
        'route' => gethostname(). '.Logger',
        'worker' => 'Workers\Logger',
        'delay' => 0,
        'workers' => [
            'logging' => false     // On / Off Queue workers logging functionality. See the Queue package docs.
                                   // You may want to turn on logs if you want to set workers as service.
        ],
    ]
);

/* End of file logger.php */
/* Location: .app/config/env/local/logger.php */