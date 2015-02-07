<?php

return array(

    'locale' => array(
        'default'  => 'en',   // This determines which set of language files should be used.
        'fallback' => 'en',   // If language not determined fallback locale will be set.
        'setCookie' => false,  // Writes locale name to cookie 
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
                        'es' => 'spanish',
                        'de' => 'deutsch',
                        'tr' => 'turkish',
                        ),

    'notice' => false,     // Puts 'translate:' texts everywhere it is help to you for multilingual development.
);

/* End of file translator.php */
/* Location: ./app/config/translator.php */
