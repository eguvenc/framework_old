<?php

return array(
    /*
    |--------------------------------------------------------------------------
    | Error
    |--------------------------------------------------------------------------
    | If debug = true framework converts all php errors to exceptions.
    */                             
    'error' => array(
        'debug' => true,       // Friendly debugging feature should be "Disabled"" in "PRODUCTION" environment.
        'reporting' => false,   // Turn on it if you want catch "unusual hidden errors", should be "Disabled"" in "PRODUCTION".
    ),
    /*
    |--------------------------------------------------------------------------
    | Http Url
    |--------------------------------------------------------------------------
    */
    'url' => array(
        'webhost' => 'framework', // Your web host name default "localhost" should be "example.com" in production config.
        'baseurl' => '/',         // Base Url "/" URL of your framework root, generally a '/' trailing slash. 
        'assets' => '/assets/',  // Assets Url of your framework generally a '/assets/' you may want to change it with your "cdn" provider.
        'rewrite' => array(
            'indexPage' => '',   // Typically this will be your index.php file, If mod_rewrite enabled is should be blank.
            'suffix'    => '',   // Allows you to add a suffix to all URLs generated by Framework.
        )
    ),
    /*
    |--------------------------------------------------------------------------
    | Log
    |--------------------------------------------------------------------------
    | @see Syslog Protocol http://tools.ietf.org/html/rfc5424
    */
    'log' =>   array(
        'control' => array(
            'enabled' => true,   // Enable / Disable logging
            'firelog' => false,  // Debug log data as html output
        ),
        'default' => array(
            'channel' => 'system',       // Default channel name should be general.
        ),
        'file' => array(
            'path' => array(
                'http'  => 'data/logs/http.log',  // Http requests log path  ( Only for File Handler )
                'cli'   => 'data/logs/cli.log',   // Cli log path  
                'ajax'  => 'data/logs/ajax.log',  // Ajax log path
            )
        ),
        'format' => array(
            'line' => '[%datetime%] %channel%.%level%: --> %message% %context% %extra%\n',  // This format just for line based log drivers.
            'date' =>  'Y-m-d H:i:s',
        ),
        'extra' => array(
            'queries'   => true,       // If true "all" SQL Queries gets logged.
            'benchmark' => true,       // If true "all" Application Benchmarks gets logged.
        ),
        'queue' => array(
            'channel' => 'Log',
            'route' => gethostname(). '.Logger',
            'worker' => 'Workers\Logger',
            'delay' => 0,
            'workers' => array(
                'logging' => false     // On / Off Queue workers logging functionality. See the Queue package docs.
            ), 
        )
    ),
    /*
    |--------------------------------------------------------------------------
    | Http Uri
    |--------------------------------------------------------------------------
    */
    'uri' => array(             // Auto detects the URI protocol 
        'protocol' => 'AUTO',   // Default option is 'AUTO', Options: REQUEST_URI, QUERY_STRING, PATH_INFO Example : http://example.com/login?param=1&param2=yes
        'permittedChars' => 'a-z 0-9~%.:_-',  // Allowed URL Characters ,this lets you specify with a regular expression which characters are permitted within your URLs.
        'queryStrings' => true,  // Allows based URLs: example.com/directory/controller?who=me&what=something&where=here
        'extensions' => array('.json','.xml'),   // Allows extension based URLs: example.com/api/get/users.json
    ),
    /*
    |--------------------------------------------------------------------------
    | Localization
    |--------------------------------------------------------------------------
    */
    'locale' => array(
        'timezone' => 'gmt',      // This pref tells the system whether to use your server's "local" time as the master now reference, or convert it to "gmt".
        'charset'  => 'UTF-8',    // This determines which character set is used by default.
        'date' => array(
            'php_date_default_timezone' => 'Europe/London',  // Sets timezone using php date_default_timezone_set(); function.
            'format' => 'H:i:s d:m:Y'
        )
     ),
    /*
    |--------------------------------------------------------------------------
    | Annotations
    |--------------------------------------------------------------------------
    */
    'annotation' => array(
        'controller' => false, // Enable or Disable "annotation reader" which is able to parse filters on controller.
    ),
    /*
    |--------------------------------------------------------------------------
    | Layers
    |--------------------------------------------------------------------------
    */
    'layer' => array(
        'cache' => false     // On / off layer global cache feature.
    ),
    /*
    |--------------------------------------------------------------------------
    | Security
    |--------------------------------------------------------------------------
    */
    'security' => array(
        'encryption' => array(
            'key' => 'write-your-secret-key',  // If you use the Encryption class you MUST set an encryption key.
        ),
        'csrf' => array(                      
            'protection'  => false,            // Enables a CSRF cookie token to be set. When set to true, token will be
            'token_name'  => 'csrf_token',     // checked on a submitted form. If you are accepting user data, it is strongly
            'cookie_name' => 'csrf_cookie',    // recommended CSRF protection be enabled.
            'expire'      => '7200',           // The number in seconds the token should expire.
         ),
    ),
    /*
    |--------------------------------------------------------------------------
    | Cookies
    |--------------------------------------------------------------------------
    */
    'cookie' => array( 
        'domain' => '',          // Set to .your-domain.com for site-wide cookies
        'path'   => '/',         // Typically will be a forward slash
        'secure' => false,       // Cookies will only be set if a secure HTTPS connection exists.
        'httpOnly' => false,     // When true the cookie will be made accessible only through the HTTP protocol
        'expire' => 604800,                     // 1 week - Cookie expire time.
        'prefix' => '',                         // Set a prefix if you need to avoid collisions
    ),
    /*
    |--------------------------------------------------------------------------
    | Proxy
    |--------------------------------------------------------------------------
    */
    'proxy' => array(     // Reverse Proxy IPs , If your server is behind a reverse proxy, you must whitelist the proxy IP
        'ips' => '',      // addresses from which the Application should trust the HTTP_X_FORWARDED_FOR
    ),                    // header in order to properly identify the visitor's IP address.
                          // Comma-delimited, e.g. '10.0.1.200,10.0.1.201'
    /*
    |--------------------------------------------------------------------------
    | Output
    |--------------------------------------------------------------------------
    */
    'output' => array(
        'compress' => false,  // Enables Gzip output compression for faster page loads.  When enabled,
    ),                        // the Response class will test whether your server supports Gzip.
                              // Even if it does, however, not all browsers support compression
                              // so enable only if you are reasonably sure your visitors can handle it
);

/* End of file config.php */
/* Location: .app/config/env/local/config.php */