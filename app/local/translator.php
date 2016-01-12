<?php

return array(

    /*
    | -------------------------------------------------------------------
    | Translator
    | -------------------------------------------------------------------
    | Locale : This determines which set of language files should be used by default.
    | Set : Write default language to cookie.
    |
    */
    'default' => [
        'locale'  => 'en',
        'set' => [
            'enabled' => true,
        ],
    ],
    'fallback' => [
        'enabled' => false,
        'locale' => 'en',
    ],
    'uri' => [
        'segment' => true,
        'segmentNumber' => 0       
    ],
    'cookie' => [
        'name'   =>'locale', 
        'domain' => null,
        'expire' => (365 * 24 * 60 * 60),
        'secure' => false,
        'httpOnly' => false,
        'path' => '/',
    ]
);