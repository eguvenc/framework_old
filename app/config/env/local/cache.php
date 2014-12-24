<?php
/*
|--------------------------------------------------------------------------
| Cache Class Configuration
|--------------------------------------------------------------------------
| Configuration file
|
*/
return array(

    'default' => array(
        'provider' => 'redis'
    ),

    'handlers' => array(
        'redis' => '\\Obullo\Cache\Handler\Redis',
        'memcache' => '\\Obullo\Cache\Handler\Memcache',
        'memcached' => '\\Obullo\Cache\Handler\Memcached',
        'apc' => '\\Obullo\Cache\Handler\Apc',
        'file' => '\\Obullo\Cache\Handler\File',
        'yourhandler' => '\\Obullo\Cache\Handler\YourHandler',  // You can create your own handler using Cache/Handler/HandlerInterface.php
    ),

   'redis' => array(

       'servers' => array(
                        array(
                          'hostname' => env('REDIS_HOST'),
                          'port'     => '6379',
                           // 'timeout'  => '2.5',  // 2.5 sec timeout, just for redis cache
                          'weight'   => '1'         // The weight parameter effects the consistent hashing 
                                                    // used to determine which server to read/write keys from.
                        ),
        ),
        'auth' =>  env('REDIS_AUTH'),       // connection password
        'serializer' =>  'SERIALIZER_PHP',  // SERIALIZER_NONE, SERIALIZER_PHP, SERIALIZER_IGBINARY
        'persistentConnect' => 0,
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
        'serializer' =>  'SERIALIZER_PHP',  // SERIALIZER_NONE, SERIALIZER_PHP, SERIALIZER_JSON, SERIALIZER_IGBINARY
    )

);

/* End of file cache.php */
/* Location: .app/env/local/cache.php */