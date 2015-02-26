<?php

return array(

    'locale' => array(
        'default'  => 'en',  // This determines which set of language files should be used by default selected.
    ),

    'fallback' => array(
        'enabled' => false,
        'locale' => 'es',   // The language will be used as a fallback for text that has no translation in the default language.
    ),

    'uri' => array(
        'segment'       => true, // Uri segment number e.g. http://example.com/en/home
        'segmentNumber' => 0       
    ),

    'cookie' => array(
        'name'   =>'locale',               // Translation value cookie name
        'expire' => (365 * 24 * 60 * 60),  // 365 day; //  @see  Cookie expire time.   http://us.php.net/strtotime
        'secure' => false,                 // Cookie will only be set if a secure HTTPS connection exists.
        'httpOnly' => false                // When true the cookie will be made accessible only through the HTTP protocol
    ),

    'languages' => array(
                        'en' => 'english', // Available Languages
                        'de' => 'deutsch',
                        'es' => 'spanish',
                        'tr' => 'turkish',
                        'fr' => 'french',
                    ),

    'debug' => false,     // Puts 'translate:' texts everywhere it is help to you for multilingual development.
);

/* End of file translator.php */
/* Location: ./app/config/translator.php */