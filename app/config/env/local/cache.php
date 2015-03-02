<?php

return array(

    'default' => array(
        'serializer' => 'php',
    ),

   'redis' => array(
       'servers' => array(
            array(
              'hostname' => $c['env']['REDIS_HOST'],
              'port'     => '6379',
               // 'timeout'  => '2.5',  // 2.5 sec timeout, just for redis cache
              'weight'   => '1'         // The weight parameter effects the consistent hashing 
                                        // used to determine which server to read/write keys from.
            )
        ),
        'auth' =>  $c['env']['REDIS_AUTH'], // Connection password
        'persistentConnect' => 0,           // Enable / Disable persistent connection, "1" on "0" off.
        'reconnectionAttemps' => 100,
    ),

    'file' => array(
        'path' =>  '/data/cache/',          // file data storage path
    ),

    'memcache' => array(
        'servers' => array(
                        array(
                          'hostname' => '',
                          'port'     => '11211',
                           // 'timeout'  => '2.5',  // 2.5 sec timeout
                        ),
        ),
    ),

    'memcached' => array(
        'servers' => array(
                        array(
                          'hostname' => '',
                          'port'     => '11211',
                          'weight'   => '1'      // The weight parameter effects the consistent hashing 
                                                 // used to determine which server to read/write keys from.
                        ),
        ),
    )

);

/* End of file cache.php */
/* Location: .app/config/env/local/cache.php */