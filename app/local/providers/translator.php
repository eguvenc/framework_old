<?php

return array(

    /*
    | -------------------------------------------------------------------
    | Translator
    | -------------------------------------------------------------------
    | Set : Write default language to cookie.
    | Locale : This determines which set of language files should be used by default.
    |
    */
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