<?php
/*
| IMPORTANT :
|
| You don't need copy / paste all of the keys
| just get configuration differences from app/local/config.php and paste here.
|
*/
return array(
                    
    'error' => array(
        'debug' => false,       // Friendly debugging feature should be "Disabled"" in "PRODUCTION" environment.
        'reporting' => false,   // Turn on it if you want catch "unusual hidden errors", should be "Disabled"" in "PRODUCTION".
    ),

    'log' =>   array(
        'control' => array(
            'enabled' => true,
            'output'  => false,
        )
    ),

    'url' => array(
        'webhost' => 'framework', // Your Virtual host name default "localhost" should be "example.com" in production config.
        'baseurl' => '/',         // Base Url "/" URL of your framework root, generally a '/' trailing slash. 
        'assets' => '/assets/',  // Assets Url of your framework generally a '/assets/' you may want to change it with your "cdn" provider.
    ),

    'cookie' => array( 
        'domain' => ''  // Set to .your-domain.com for site-wide cookies

    ),

);

/* End of file config.php */
/* Location: .app/config/env/test/config.php */