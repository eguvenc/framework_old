<?php

return array(

    'params' => [

        'default' => [
            'set' => true,
            'locale'  => 'en',
            'languages' => [
                'en',
                'es',
                'tr',
            ]
        ],
        'fallback' => [
            'enabled' => false,
            'locale' => 'en',
        ],
        'uri' => [
            'enabled' => true,
            'segment' => 0       
        ],
        'cookie' => [
            'name'   =>'locale', 
            'domain' => null,
            'expire' => (365 * 24 * 60 * 60),
            'secure' => false,
            'httpOnly' => false,
            'path' => '/',
        ]
    ]

);