<?php

return array(

    /*
    | -------------------------------------------------------------------
    | Translator
    | -------------------------------------------------------------------
    | Debug : Puts "translate:" texts everywhere. it is help to you for multilingual development.
    | Locale : This determines which set of language files should be used by default.
    | Set :   Write default language to cookie.
    |
    */
    'debug' => false,

    'default' => [
        'locale'  => 'en',
        'set' => [
            'enabled' => true,
        ],
    ],
    'fallback' => [
        'enabled' => false,
        'locale' => 'es',
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
    ],
    'languages' => [
        'en' => 'english',
        'tr' => 'turkish',
    ],
);