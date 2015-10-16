<?php

return array(
    
    'params' => [
        'provider' => [
            'name' => 'cache',
            'params' => [
                'driver' => 'redis',
                'connection' => 'default'
            ]
        ]
    ],
    'methods' => [
        'setParameters' => [
            'registerSaveHandler' => '\Obullo\Session\SaveHandler\Cache',
            'setName' => '',
            'start' => '',
        ]
    ]
);