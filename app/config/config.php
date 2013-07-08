<?php

/*
|--------------------------------------------------------------------------
| Base Settings
|--------------------------------------------------------------------------
|
|    Base Url      "/"                       URL to your Obullo root, generally a '/' trailing slash.
|    Domain Name   "Domain Name"             Name of your domain like Obullo. ( without .com ).
|
*/
$config['base_url']              = '/';
$config['domain_name']           = 'yourdomainname';

/*
|--------------------------------------------------------------------------
| SSL Config
|--------------------------------------------------------------------------
| Note : 
| if your HTTP server NGINX add below the line to your fastcgi_params file.
|  
|   # fastcgi_param  HTTPS		  $ssl_protocol;
|
| then $_SERVER['HTTPS'] variable will be available for PHP (fastcgi).
*/
$config['ssl']                   = (ENV == 'LIVE') ? TRUE : FALSE;

/*
|--------------------------------------------------------------------------
| Public Site URL
|--------------------------------------------------------------------------
|
| URL to your Asset Files, Like Base URL. 
|
*/
$config['public_url']            = '/';

/*
|--------------------------------------------------------------------------
| Obullo Error Handler Enable / Disable Displaying Errors
|--------------------------------------------------------------------------
|  
| Obullo use error_reporting function default as error_reporting(0), 
| however Obullo can catch all php errors and show them friendly.
|
| @see error constants http://usphp.com/manual/en/errorfunc.constants.php
|
|   0 - Turn off all error reporting (0)
|   1 - All errors  = E_ALL;
|   OR String (Custom Regex)
|
|   String - Custom Regex Mode Examples:
|
|   Running errors
|       $config['error_reporting'] = 'E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR';
|   
|   Running errors + notices
|       $config['error_reporting'] = 'E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_NOTICE';
|   
|   All errors except notices, warnings, exceptions and database errors
|       $config['error_reporting'] = 'E_ALL ^ (E_NOTICE | E_WARNING | E_EXCEPTION | E_DATABASE)';
|       
|   All errors except notices 
|       $config['error_reporting'] = 'E_ALL ^ E_NOTICE';
*/
$config['error_reporting']       = (ENV == 'LIVE') ? 0 : 1;

/*
|--------------------------------------------------------------------------
| Enable / Disable Advanced Debugging
|--------------------------------------------------------------------------
|
| Enabling advanced debug mode will help you to easy development, if it is
| enabled Obullo will give you more details about application errors.
| For Object dump you will need 32M php memory.To dumping large objects
| increase your memory_limit from your php.ini file.
|       
|   To Disable Debugging: array('enabled' => FALSE, 'padding' => 5);
|   
|   Custom regexs same as error reporting.
*/
$config['debug_backtrace']       = array('enabled' => 'E_ALL ^ (E_NOTICE | E_WARNING)', 'padding' => 5);

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
|
| If you have enabled error logging, you can set an error threshold to 
| determine what gets logged. Threshold options are:
| You can enable error logging by setting a threshold over zero. The
| threshold determines what gets logged. Threshold options are:
|
|    0 = Disables logging, Error logging TURNED OFF
|    1 = Error Messages (including PHP errors)
|    2 = Debug Messages
|    3 = Informational Messages
|    4 = Benchmark Info
|    5 = All Messages
|
| For a live site you'll usually only enable Errors (1) to be logged otherwise
| your log files will fill up very fast.
|
|  o When $config['log_queries']   == TRUE ALL Database SQL Queries goes to the log file.
|  o When $config['log_benchmark'] == TRUE ALL framework benchmarks goes to the log file.
|
*/
$config['log_threshold']         = (ENV == 'LIVE') ? 1 : 5;
$config['log_queries']           = (ENV == 'LIVE') ? FALSE : TRUE;
$config['log_benchmark']         = (ENV == 'LIVE') ? FALSE : TRUE;
$config['log_date_format']       = 'Y-m-d H:i:s';

/*
|--------------------------------------------------------------------------
| Master Time Reference
|--------------------------------------------------------------------------
|
| Options are "local" or "gmt".  This pref tells the system whether to use
| your server's local time as the master "now" reference, or convert it to
| GMT.  See the "date helper" page of the user guide for information
| regarding date handling.
|
*/
$config['time_reference']        = 'local';

/*
|--------------------------------------------------------------------------
| Index File
|--------------------------------------------------------------------------
|
| Typically this will be your index.php file, unless you've renamed it to
| something else. If you are using mod_rewrite to remove the page set this
| variable so that it is blank.
|
*/
$config['index_page']            = 'index.php';

/*
|--------------------------------------------------------------------------
| URI PROTOCOL
|--------------------------------------------------------------------------
|
| This item determines which server global should be used to retrieve the
| URI string.  The default setting of "AUTO" works for most servers.
| If your links do not seem to work, try one of the other delicious flavors:
|
| 'AUTO'            Default - auto detects
| 'PATH_INFO'       Uses the PATH_INFO
| 'QUERY_STRING'    Uses the QUERY_STRING
| 'REQUEST_URI'     Uses the REQUEST_URI
| 'ORIG_PATH_INFO'  Uses the ORIG_PATH_INFO
|
|  You can use parameters like http://example.com/login?param=1&param2=yes
|
*/
$config['uri_protocol']          = 'AUTO';  // Generally AUTO for Apache and REQUEST_URI FOR nginx.

/*
|--------------------------------------------------------------------------
| URI EXTENSIONS
|--------------------------------------------------------------------------
|
| This item determines allowed uri extensions, if you request an uri like
| this : http://example.com/welcome/example.json Obullo URI class assign
| extension name to $this->uri->extension(); function.
| Default extension is " php ".
|
*/
$config['uri_extensions']        = array('php', 'html', 'json', 'xml', 'raw', 'rss', 'ajax');

/*
|--------------------------------------------------------------------------
| URL suffix
|--------------------------------------------------------------------------
|
| This option allows you to add a suffix to all URLs generated by Obullo.
| For more information please see the user guide:
|
| Chapters / General Topics / Obullo Urls
|
*/
$config['url_suffix']            = '';

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
|
| This determines which set of language files should be used. Make sure
| there is an available translation if you intend to use something other
| than english.
|
*/
$config['language']              = 'english';

/*
|--------------------------------------------------------------------------
| Default Character Set
|--------------------------------------------------------------------------
|
| This determines which character set is used by default in various methods
| that require a character set to be provided.
|
*/
$config['charset']               = 'UTF-8';

/*
|--------------------------------------------------------------------------
| Allowed URL Characters
|--------------------------------------------------------------------------
|
| This lets you specify with a regular expression which characters are permitted
| within your URLs.  When someone tries to submit a URL with disallowed
| characters they will get a warning message.
|
| As a security measure you are STRONGLY encouraged to restrict URLs to
| as few characters as possible.  By default only these are allowed: a-z 0-9~%.:_-
|
| Leave blank to allow all characters -- but only if you are insane.
|
| DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
|
*/
$config['permitted_uri_chars']   = 'a-z 0-9~%.:_-';

/*
|--------------------------------------------------------------------------
| Enable Query Strings
|--------------------------------------------------------------------------
|
| By default Obullo uses search-engine friendly segment based URLs:
| example.com/who/what/where/
|
| You can optionally enable standard query string based URLs:
| example.com?who=me&what=something&where=here
|
| Options are: TRUE or FALSE (boolean)
|
| The other items let you set the query string "words" that will
| invoke your controllers and its functions:
| example.com/index.php?d=directory&c=controller&m=function
|
| Please note that some of the helpers won't work as expected when
| this feature is enabled, since Obullo is designed primarily to
| use segment based URLs.
|
*/
$config['enable_query_strings']  = TRUE;
$config['directory_trigger']     = 'd';   
$config['controller_trigger']    = 'c';
$config['function_trigger']      = 'm';

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
|
| If you use the Encryption class or the Sessions helper with encryption
| enabled you MUST set an encryption key.  See the user guide for info.
|
*/
$config['encryption_key']        = "write-your-secret-key";

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
|
| 'sess_cookie_name'    = the name you want for the cookie
| 'sess_encrypt_cookie' = TRUE/FALSE (boolean).  Whether to encrypt the cookie
| 'sess_expiration'     = the number of SECONDS you want the session to last.
| 'sess_die_cookie'     = If set TRUE all sessions  will destroy when the browser closed.
|  by default sessions last 7200 seconds (two hours).  Set to zero for no expiration.
| 'time_to_update'      = how many seconds between Obullo refreshing Session Information
| 'sess_db_var'         = normally Obullo use standart '$this->db' variable for session database
|                         crud operations, you can change it like db2, db3 .. 
|
*/
$config['sess_cookie_name']      = 'ob_session';
$config['sess_expiration']       = 7200;
$config['sess_die_cookie']       = FALSE;
$config['sess_encrypt_cookie']   = TRUE;
$config['sess_driver']           = 'native';  // cookie | database | mongodb
$config['sess_db_var']           = 'db';            
$config['sess_table_name']       = 'ob_sessions';
$config['sess_match_ip']         = FALSE;
$config['sess_match_useragent']  = TRUE;
$config['sess_time_to_update']   = 300;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
|
| 'cookie_prefix' = Set a prefix if you need to avoid collisions
| 'cookie_domain' = Set to .your-domain.com for site-wide cookies
| 'cookie_path'   = Typically will be a forward slash
| 'cookie_secure' = Cookies will only be set if a secure HTTPS connection exists.
|  
|  VERY IMPORTANT: For all cookie_time expirations, time() function must 
|  be at the end. Otherwise some cookie and session die functions will not work.
|
*/
$config['cookie_prefix']         = '';
$config['cookie_domain']         = (ENV == 'LIVE') ? '.your-domain.com' : '';
$config['cookie_path']           = '/';
$config['cookie_time']           = (7 * 24 * 60 * 60) + time();
$config['cookie_secure']	 = FALSE;

/*
|--------------------------------------------------------------------------
| Rewrite PHP Short Tags
|--------------------------------------------------------------------------
|
| If your PHP installation does not have short tag support enabled, Obullo
| can rewrite the tags on-the-fly, enabling you to utilize that syntax
| in your view files.  Options are TRUE or FALSE (boolean)
|
*/
$config['rewrite_short_tags']    = FALSE;

/*
|--------------------------------------------------------------------------
| Global XSS Filtering
|--------------------------------------------------------------------------
|
| Determines whether the XSS filter is always active when GET, POST or
| COOKIE data is encountered
|
*/
$config['global_xss_filtering']  = FALSE;
                            
/*
|--------------------------------------------------------------------------
| Cross Site Request Forgery
|--------------------------------------------------------------------------
| Enables a CSRF cookie token to be set. When set to TRUE, token will be
| checked on a submitted form. If you are accepting user data, it is strongly
| recommended CSRF protection be enabled.
|
| 'csrf_token_name'  = The token name
| 'csrf_cookie_name' = The cookie name
| 'csrf_expire'      = The number in seconds the token should expire.
*/
$config['csrf_protection']       = FALSE;
$config['csrf_token_name']       = 'csrf_token_name';
$config['csrf_cookie_name']      = 'csrf_cookie_name';
$config['csrf_expire']           = 7200;

/*
|--------------------------------------------------------------------------
| Reverse Proxy IPs
|--------------------------------------------------------------------------
|
| If your server is behind a reverse proxy, you must whitelist the proxy IP
| addresses from which Obullo should trust the HTTP_X_FORWARDED_FOR
| header in order to properly identify the visitor's IP address.
| Comma-delimited, e.g. '10.0.1.200,10.0.1.201'
|
*/
$config['proxy_ips']             = '';

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
|
| Enables Gzip output compression for faster page loads.  When enabled,
| the output class will test whether your server supports Gzip.
| Even if it does, however, not all browsers support compression
| so enable only if you are reasonably sure your visitors can handle it.
|
| VERY IMPORTANT:  If you are getting a blank page when compression is enabled it
| means you are prematurely outputting something to your browser. It could
| even be a line of whitespace at the end of one of your scripts.  For
| compression to work, nothing can be sent before the output buffer is called
| by the output class.  Do not "echo" any values with compression enabled.
|
*/
$config['compress_output']       = FALSE;


/* End of file config.php */
/* Location: .app/config/config.php */