<?php

/**
|--------------------------------------------------------------------------
| Obullo Command Line Bootstrap.php file.
|--------------------------------------------------------------------------
| For Command Line operations we use Cli_Bootstrap file.
|
| @see User Guide: Chapters / General Topics / Tasks
|
*/

/**
|--------------------------------------------------------------------------
| Set Command Line Server Headers
|--------------------------------------------------------------------------
*/ 
$_SERVER['HTTP_USER_AGENT']     = 'Obullo Command Line';
$_SERVER['HTTP_ACCEPT_CHARSET'] = 'utf-8';

/**
|--------------------------------------------------------------------------
| Disable Deprecated Zend Mode
|-----------------------------------------------------defined('BASE') or exit('Access Denied!');---------------------
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

// Put your functions here ..

/* End of file Cli_Bootstrap.php */
/* Location: .app/core/Cli_Bootstrap.php */