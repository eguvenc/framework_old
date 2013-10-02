<?php

/*
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
|     o DEBUG - Debug Mode  ( Quick Debugging, Show All Php Native Errors )
|     o TEST  - Testing     ( Test mode, behaviours like LIVE )
|     o LIVE  - Production  ( Production mode, all errors disabled )
|
*/
define('ENV', 'DEBUG');

/*
|--------------------------------------------------------------------------
| Native PHP Error Handler (Default Off) 
|--------------------------------------------------------------------------
| For security reasons its default off.
| But default `Error Handler` is active if you don't want to use Framework
| development error handler you can *turn off it easily from "app/config/config.php" 
| file.
|
*/
error_reporting(0);

/*
|--------------------------------------------------------------------------
| Set Default Time Zone Identifer.
|--------------------------------------------------------------------------
|                                                                 
| Set the default timezone identifier for date function ( Server Time )
| @see  http://www.php.net/manual/en/timezones.php
| 
*/
date_default_timezone_set('America/Chicago');

/*
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

/*
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
             
/*
|--------------------------------------------------------------------------
| Obullo Command Line ( Tasks )
|--------------------------------------------------------------------------
|  @see User Guide: Chapters / General Topics / Tasks
|
*/ 
if(defined('STDIN')) 
{
    /*
    |--------------------------------------------------------------------------
    | Set Command Line Server Headers
    |--------------------------------------------------------------------------
    */ 
    $_SERVER['HTTP_USER_AGENT']     = 'Framework CLI';
    $_SERVER['HTTP_ACCEPT_CHARSET'] = 'utf-8';

    /*
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

    /*
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

/*
|--------------------------------------------------------------------------
| Upgrading to new version.
|--------------------------------------------------------------------------
| If a new version available, package manager upgrage it using your package.json.
| If you need a stable a version remove asteriks ( * ), and set version to specific
| number. ( e.g. version: "2.0" )
|
| {
|  "dependencies": {
|     "obullo": "*",
|     "auth" : "3.0"
|  }
| }
|
 */
require (APP .'cache'. DS .'packages.cache');

/*
|--------------------------------------------------------------------------
| Framework Component
|--------------------------------------------------------------------------
*/
$core = strtolower($packages['components']['core']);  // Custom core, you can use another core instead of Obullo.

require (PACKAGES .$core. DS .'releases'. DS .$packages['dependencies'][$core]['version']. DS .$core. EXT);

runFramework();