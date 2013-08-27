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
| Set Default Time Zone Identifer.
|--------------------------------------------------------------------------
|                                                                 
| Set the default timezone identifier for date function ( Server Time )
| @see  http://www.php.net/manual/en/timezones.php
| 
*/
date_default_timezone_set('America/Chicago');

/**
|--------------------------------------------------------------------------
| Application Constants.
|--------------------------------------------------------------------------
| This file specifies which APP constants should be loaded by default.
|
 */
if( ! defined('ROOT'))
{
    require ('constants');
}

/**
|--------------------------------------------------------------------------
| Disable Deprecated Zend Mode
|--------------------------------------------------------------------------
|
| Enable compatibility mode with Zend Engine 1 (PHP 4). It affects the cloning, 
| casting (objects with no properties cast to false or 0), and comparing of objects. 
| In this mode, objects are passed by value instead of reference by default.
| 
| This feature has been DEPRECATED and REMOVED as of PHP 5.3.0. 
| It should be '0'. 
| 
*/
ini_set('zend.ze1_compatibility_mode', 0); 
             
/**
|--------------------------------------------------------------------------
| Obullo Command Line ( Tasks )
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
    $_SERVER['HTTP_USER_AGENT']     = 'Obullo CLI';
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


/**
|--------------------------------------------------------------------------
| Loads the (static) config files.
|--------------------------------------------------------------------------
| This function help to load your static configuration files.
 */

/**
* @access    private
* @author    Obullo Team
* @param     string $filename file name
* @param     string $var variable of the file
* @param     string $folder folder of the file
* @param     string $ext extension of the file
* @return    array
*/
function getStatic($filename = 'config', $var = '', $folder = '', $ext = '')
{
    static $loaded    = array();
    static $variables = array();

    $key = trim($folder. DS .$filename. $ext);
    if ( ! isset($loaded[$key]))
    {
        require($folder. DS .$filename. $ext);
    
        if($var == '') { $var = &$filename; }

        if ( ! isset($$var) OR ! is_array($$var))
        {
            die('The static file '. $folder. DS .$filename. $ext.' file does not appear to be formatted correctly.');
        }

        $variables[$key] =& $$var;
        $loaded[$key] = $key;
     }

    return $variables[$key];
}

/**
|--------------------------------------------------------------------------
| Loads the app config files.
|--------------------------------------------------------------------------
| This function load your configuration files from "app/config" folder.
| 
 */

/**
* @access   public
* @param    string $filename
* @param    string $var
* @return   array
*/
function getConfig($filename = 'config', $var = '', $folder = '', $extension = '')
{
    $ext    = ($extension == '') ? EXT : $extension;
    $folder = ($folder == '') ? APP .'config' : $folder;
    
    if(strpos($filename, '.') > 0)  // removes the extension for "." config files.
    {   
        $ext = ''; 
        $var = 'packages';
    }
    
    return getStatic($filename, $var, $folder, $ext);
}

/**
|--------------------------------------------------------------------------
| Upgrading to new version.
|--------------------------------------------------------------------------
| If a new version available, package manager upgrage it using your package.json.
| If you need a stable a version remove "*", and set version to specific
| number. ( e.g. version: "2.0" )
|
| {
|  "dependencies": {
|     "obullo": "*",   // (*) = new version, (2.1,2.2 .. ) = stable version
|     "auth" : "*"
|  }
| }
|
 */
$packages = getConfig('packages.cache');

/**
|--------------------------------------------------------------------------
| Framework Cartdrige
|--------------------------------------------------------------------------
*/
$core = $packages['core'];  // Custom core, you can use another core instead of obullo.

require (OB_MODULES .$core. DS .'releases'. DS .$packages['dependencies'][$core]['version']. DS .$core. EXT);

$framework = ucfirst($core);

// --------------------------------------------------------------------

$obullo = new $framework;
$obullo->run();