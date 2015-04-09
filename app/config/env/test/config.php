<?php
/*
| IMPORTANT :
|
| You don't need copy / paste all of the keys
| just get configuration differences from app/local/config.php and paste here.
|
*/
return array(
                    
    'error' => [
        'debug' => true,       // Friendly debugging feature should be "Disabled"" in "PRODUCTION" environment.
    ],

    'log' =>   [
        'enabled' => true,
        'debug'  => false,
    ],

    'url' => [
        'webhost' => 'framework', // Your Virtual host name default "localhost" should be "example.com" in production config.
        'baseurl' => '/',         // Base Url "/" URL of your framework root, generally a '/' trailing slash. 
        'assets' => '/assets/',  // Assets Url of your framework generally a '/assets/' you may want to change it with your "cdn" provider.
    ],

    'cookie' => [
        'domain' => ''  // Set to .your-domain.com for site-wide cookies

    ],

);

/* End of file config.php */
/* Location: .app/config/env/test/config.php */