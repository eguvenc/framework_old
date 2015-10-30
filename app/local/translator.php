<?php

return array(

    /*
    | -------------------------------------------------------------------
    | Translator
    | -------------------------------------------------------------------
    | Locale settings
    | 
    | Debug : Puts "translate:" texts everywhere. it is help to you for multilingual development.
    | Locale : This determines which set of language files should be used by default.
    |
    */
    'default' => [

        'debug' => false,
        'locale'  => 'en'
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