<?php

/**
|--------------------------------------------------------------------------
| APPLICATION ENVIRONMENT
|--------------------------------------------------------------------------
|
| You can load different configurations depending on your
| current environment. Setting the environment also influences
| things like logging and error reporting.
|
| This can be set to anything, but default usage is:
|
|     o DEV   - Development ( Show Obullo Friendly Errors )
|     o DEBUG - Debug Mode  ( Quick Debugging, Show Hidden Php Native Errors )
|     o TEST  - Testing     ( Test mode, behaviours like DEV )
|     o LIVE  - Production  ( Close all errors )
|
| NOTE: If you change these, also change the error_reporting() code below
|
*/
define('ENV', 'DEBUG');

/**
|--------------------------------------------------------------------------
| Native PHP Error Handler (Default Off) 
|--------------------------------------------------------------------------
| For security reasons its default off.
| But default `Obullo Error Handler` is active if you don't want to use Obullo
| development error handler you can *turn off it easily from 
| "app/config/config.php" file.
|
*/              
if (defined('ENV'))
{
    switch(ENV)
    {
        case 'DEV':
            error_reporting(E_ALL | E_STRICT);
            ini_set('display_errors', '1');
            error_reporting(0); // Show errors using Obullo Error Handler
            break;
        
        case 'TEST':
            error_reporting(E_ALL | E_STRICT);
            error_reporting(0);
            break;
        
        case 'DEBUG':
            error_reporting(E_ALL | E_STRICT);
            ini_set('display_errors', '1');
            break;
        
        case 'LIVE':
            error_reporting(0);
            break;
        
        default:
        exit('The application environment is not set correctly.');
    }   
}

/**
|--------------------------------------------------------------------------
| DIRECTORY SEPERATOR
|--------------------------------------------------------------------------
| Friendly Directory Seperator
|
*/
define('DS',   DIRECTORY_SEPARATOR);

/**
|--------------------------------------------------------------------------
| Set Default Time Zone Identifer.
|--------------------------------------------------------------------------
|                                                                 
| Set the default timezone identifier for date function ( Server Time )
| @see  http://www.php.net/manual/en/timezones.php
| 
*/
date_default_timezone_set('America/Chicago');

/**
|---------------------------------------------------------------
| FOLDER CONSTANTS
|---------------------------------------------------------------
*/
define('ROOT',  realpath(dirname(__FILE__)) . DS);
define('BASE', ROOT .'obullo'. DS);
define('APP',  ROOT .'app'. DS);
define('MODULES',  ROOT .'modules'. DS);
define('OB_MODULES',  ROOT .'ob_modules'. DS);

/**
|---------------------------------------------------------------
| UNDERSTANDING CONSTANTS
|---------------------------------------------------------------
| DS          - The DIRECTORY SEPERATOR
| EXT         - The file extension.  Typically ".php"
| SELF        - The name of THIS file (typically "index.php")
| FCPATH      - The full server path to THIS file
| PHP_PATH    - The php path of your server
| FPATH       - The full server path without file
| ROOT        - The root path of your server
| BASE        - The full server path to the "obullo" folder
| APP         - The full server path to the "application" folder
| MODULES     - The full server path to the "modules" folder
| TASK_FILE   - Set your task (CLI) file name that we use it in task helper.
*/
define('EXT',  '.php');
define('FCPATH', __FILE__);
define('PHP_PATH', '/usr/bin/php'); 
define('FPATH', dirname(__FILE__));  
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('TASK_FILE', 'task');

/**
|--------------------------------------------------------------------------
| Disable Deprecated Zend Mode
|--------------------------------------------------------------------------
|
| Enable compatibility mode with Zend Engine 1 (PHP 4). It affects the cloning, 
| casting (objects with no properties cast to FALSE or 0), and comparing of objects. 
| In this mode, objects are passed by value instead of reference by default.
| 
| This feature has been DEPRECATED and REMOVED as of PHP 5.3.0. 
| It should be '0'. 
| 
*/
ini_set('zend.ze1_compatibility_mode', 0); 
             
/**
|--------------------------------------------------------------------------
| Obullo Command Line
|--------------------------------------------------------------------------
|  @see User Guide: Chapters / General Topics / Tasks
|
*/ 
if(defined('STDIN')) 
{
    /**
    |--------------------------------------------------------------------------
    | Set Command Line Server Headers
    |--------------------------------------------------------------------------
    */ 
    $_SERVER['HTTP_USER_AGENT']     = 'Obullo Command Line';
    $_SERVER['HTTP_ACCEPT_CHARSET'] = 'utf-8';

    /**
    |--------------------------------------------------------------------------
    | Set Execution Limit
    |--------------------------------------------------------------------------
    | 
    | Limits the maximum execution time. 0 = Unlimited.
    | Set the number of seconds a script is allowed to run. If this is reached, 
    | the script returns a fatal error. The default limit is 30 seconds or, if it exists, 
    | the max_execution_time value defined in the php.ini.
    | 
    */
    set_time_limit(0);

    /**
    |--------------------------------------------------------------------------
    | Set Memory limit
    |--------------------------------------------------------------------------
    |
    | Increase the maximum amount of memory available to PHP Cli
    | operations.
    | 
    */
    ini_set('memory_limit', '100000M');
}

// --------------------------------------------------------------------

/**
* Loads the (static) config or language files.
*
* @access    private
* @author    Obullo Team
* @param     string $filename file name
* @param     string $var variable of the file
* @param     string $folder folder of the file
* @return    array
*/
function get_static($filename = 'config', $var = '', $folder = '')
{
    static $loaded    = array();
    static $variables = array();
    
    $key = trim($folder. DS .$filename. EXT);
    
    if ( ! isset($loaded[$key]))
    {
        require($folder. DS .$filename. EXT);
     
        if($var == '') 
        {
            $var = &$filename;
        }

        if($filename != 'autoload' AND $filename != 'constants')
        {
            if ( ! isset($$var) OR ! is_array($$var))
            {
                $error_msg = 'The static file '. $folder. DS .$filename. EXT .' file does not appear to be formatted correctly.';
                
                log_me('debug', $error_msg);
                
                throw new Exception($error_msg);
            }
        }

        $variables[$key] =& $$var;
        $loaded[$key] = $key;
     }

    return $variables[$key];
}

// --------------------------------------------------------------------

/**
* Get config file.
*
* @access   public
* @param    string $filename
* @param    string $var
* @return   array
*/
function get_config($filename = 'config', $var = '', $folder = '')
{
    $folder = ($folder == '') ? APP .'config' : $folder;
    
    if($filename == 'database')
    {
        $database   = get_static($filename, $var, APP .'config');

        return $database;
    }
    
    return get_static($filename, $var, $folder);
}

$packages = get_config('packages');


// --------------------------------------------------------------------

require (OB_MODULES .'obullo'. DS .'releases'. DS .$packages['version']. DS .'obullo'. EXT);

// --------------------------------------------------------------------


$obullo = new Obullo();
$obullo->run();