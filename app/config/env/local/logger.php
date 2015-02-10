<?php

return array(

    'default' => array(
        'channel' => 'system',       // Default channel name should be general.
    ),
    
    'file' => array(
        'path' => array(
            'http'  => 'data/logs/http.log',  // Http requests log path  ( Only for File Handler )
            'cli'   => 'data/logs/cli.log',   // Cli log path  
            'ajax'  => 'data/logs/ajax.log',  // Ajax log path
        )
    ),

    'format' => array(
        'line' => '[%datetime%] %channel%.%level%: --> %message% %context% %extra%\n',  // This format just for line based log drivers.
        'date' =>  'Y-m-d H:i:s',
    ),
    
    'extra' => array(
        'queries'   => true,       // If true "all" SQL Queries gets logged.
        'benchmark' => true,       // If true "all" Application Benchmarks gets logged.
    ),

    'queue' => array(
        'channel' => 'Log',
        'route' => gethostname(). '.Logger',
        'worker' => 'Workers\Logger',
        'delay' => 0,
        'workers' => array(
            'logging' => false     // On / Off Queue workers logging functionality. See the Queue package docs.
                                   // You may want to turn on logs if you want to set workers as service.
        ), 
    )
);

/* End of file logger.php */
/* Location: .app/config/env/local/logger.php */
