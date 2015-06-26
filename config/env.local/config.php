<?php
/*
|--------------------------------------------------------------------------
| General Config
|--------------------------------------------------------------------------
| This file specifies the global configurations of your application.
*/
return array(

    /**
     * Errors
     * 
     * Debug : Friendly debugging feature disable it in "production" environment. If log enabled
     *         all errors go to log data otherwise framework use php native errors.
     */
    'error' => [
        'debug' => true,
    ],

    /**
     * Url
     * 
     * Webhost : Your web host name default "localhost" should be "example.com" in production config
     * baseurl : Base Url "/" URL of your framework root, generally a '/' trailing slash. 
     * assets
     *     url    : Assets url of your framework generally a '/' you may want to change it with your "cdn" provider.
     *     folder : Full path of assets folder
     * rewrite : 
     *     index.php : Typically this will be your index.php file, If mod_rewrite enabled is should be blank. 
     *     suffix    : Allows you to add a suffix to all URLs generated by Framework.
     */
    'url' => [
        'webhost'  => 'framework',
        'baseurl'  => '/',
        'assets'   => [
            'url' => '/',
            'folder' => '/resources/assets/',
        ],    
        'rewrite' => [
            'index.php' => '',
            'suffix'  => '',
        ]
    ],

    /**
     * Log
     *
     * Enabled: On / off logging ( If log disabled run php native errors )
     */
    'log' => [
        'enabled' => true,
    ],

    /**
     * Debugger
     *
     * Enabled : On / off http debugger web socket data activity
     * socket  : Websocket connection url and port
     */
    'http' => [
        'debugger' => [
            'enabled' => false,
            'socket' => 'ws://127.0.0.1:9000'
        ]
    ],
    
    /**
     * Uri
     *
     * Protocol     : Options: REQUEST_URI, QUERY_STRING, PATH_INFO Example : http://example.com/login?param=1&param2=yes
     * sanitizer    : If true removes all characters except letters, digits and $-_.+!*'(],{}|\\^~[]`<>#%";/?:@&=.
     * queryStrings : Allows query string based URLs: http://example.com/directory/controller?who=me&what=something&where=here
     * extensions   :  Allows extension based URLs: example.com/api/get/users.json
     */
    'uri' => [
        'protocol' => 'REQUEST_URI',
        'sanitizer' => true,
        'queryStrings' => true,
        'extensions' => ['.json','.xml','.html'],
    ],

    /**
     * Locale
     *
     * Timezone : This pref tells the system whether to use your server's "local" time as the master now reference, or convert it to "gmt".
     * charset  : This pref determines which character set is used by default.
     * date:
     *   php_date_default_timezone : Sets timezone using php date_default_timezone_set(); function.
     *   format : Sets default application date format.
     */
    'locale' => [
        'timezone' => 'gmt',
        'charset'  => 'UTF-8',
        'date' => [
            'php_date_default_timezone' => 'Europe/London',
            'format' => 'H:i:s d:m:Y'
        ]
     ],

     /**
      * Controller
      *
      * Annotations : On / off annotations. If on you can use them for middlewares, events etc.
      */
    'controller' => [
        'annotations' => true,
    ],

    /**
     * Layers
     *
     * Cache : On / off layer cache feature.
     */
    'layer' => [
        'cache' => false
    ],

    /**
     * Cookies
     *
     * Domain   : Set to .your-domain.com for site-wide cookies
     * path     : Typically will be a forward slash,
     * secure   : Cookies will only be set if a secure HTTPS connection exists.
     * httpOnly : When true the cookie will be made accessible only through the HTTP protocol
     * expire   : 1 week - Cookie expire time
     * prefix   : Set a prefix if you need to avoid collisions
     */
    'cookie' => [ 
        'domain' => $c['env']['COOKIE_DOMAIN.null'],
        'path'   => '/',
        'secure' => false,
        'httpOnly' => false,
        'expire' => 604800,
        'prefix' => '',
    ],

    /**
     * Proxy
     *
     * Ips : Reverse Proxy IPs , If your server is behind a reverse proxy, you must whitelist the proxy IP
     *       addresses from which the Application should trust the HTTP_X_FORWARDED_FOR
     *       header in order to properly identify the visitor's IP address.
     *       Comma-delimited, e.g. '10.0.1.200,10.0.1.201'
     */
    'proxy' => [
        'ips' => '',
    ],
);

/* End of file config.php */
/* Location: .config/env.local/config.php */