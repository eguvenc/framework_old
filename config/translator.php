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

    /**
     * Fallback language
     *
     * Enabled : On / off
     * Locale  : The language will be used as a fallback for text that has no translation in the default language.
     */
    'fallback' => [
        'enabled' => false,
        'locale' => 'es',
    ],

    /**
     * Uri configuration
     * 
     * Segment : On / off
     * SegmentNumber : Uri segment number e.g. http://example.com/en/home
     */
    'uri' => [
        'segment' => true,
        'segmentNumber' => 0       
    ],

    /**
     * Cookie configuration
     * 
     * Name : Translation value cookie name
     * Domain : Set to .your-domain.com for site-wide cookies
     * Expire : 365 day. @see  Cookie expire time. http://us.php.net/strtotime
     * Secure : Cookie will only be set if a secure HTTPS connection exists.
     * HttOnly : When true the cookie will be made accessible only through the HTTP protocol
     * Path : Cookie domain path
     */
    'cookie' => [
        'name'   =>'locale', 
        'domain' => $c['env']['COOKIE_DOMAIN.null'],
        'expire' => (365 * 24 * 60 * 60),
        'secure' => false,
        'httpOnly' => false,
        'path' => '/',
    ],

    /**
     * Available Languages
     */
    'languages' => [
                        'en' => 'english',
                        'de' => 'deutsch',
                        'es' => 'spanish',
                        'tr' => 'turkish',
                        'fr' => 'french',
                    ],
);