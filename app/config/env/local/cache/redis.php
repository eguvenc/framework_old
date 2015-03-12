<?php

return array(

    'connections' => array(

        'default' => array(         // Default connection always use serializer none

            'host' => $c['env']['REDIS_HOST'],
            'port' => 6379,
            'options' => array(
                'auth' => $c['env']['REDIS_AUTH'], // Connection password
                'timeout' => 30,
                'persistent' => 0,
                'reconnection.attemps' => 100,     // For persistent connections
                'serializer' => 'none',
                'database' => null,
                'prefix' => null,
            )
        ),

        'second' => array(         // Second connection always use serializer php

            'host' => $c['env']['REDIS_HOST'],
            'port' => 6379,
            'options' => array(
                'auth' => $c['env']['REDIS_AUTH'], // Connection password
                'timeout' => 30,
                'persistent' => 0,
                'reconnection.attemps' => 100,     // For persistent connections
                'serializer' => 'php',
                'database' => null,
                'prefix' => null,
            )
        ),

    ),

    'nodes' => array(
        [
            // 'host' => null,
            // 'port' => 6379,
        ]
    ),

);

/* End of file redis.php */
/* Location: .app/config/env/local/cache/redis.php */