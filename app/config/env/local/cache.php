<?php
/*
|--------------------------------------------------------------------------
| Cache Class Configuration
|--------------------------------------------------------------------------
| Configuration file
|
*/
return array(
   'servers' => array(
                    array(
                      'hostname' => '10.0.0.154',
                      'port'     => '6379',
                       // 'timeout'  => '2.5',  // 2.5 sec timeout, just for redis cache
                      'weight'   => '1'         // The weight parameter effects the consistent hashing 
                                                // used to determine which server to read/write keys from.
                    ),
    ),
    'auth' =>  'aZX0bjL',            // connection password
    'path' =>  '/data/cache/',  // file storage path just for file cache
    'serializer' =>  'SERIALIZER_PHP',     // SERIALIZER_NONE, SERIALIZER_PHP, SERIALIZER_IGBINARY
);

/* End of file cache.php */
/* Location: .app/env/local/cache.php */