<?php
/**
|--------------------------------------------------------------------------
| Obullo Command Line Tool (TASKS)
|--------------------------------------------------------------------------
| Obullo Cli Easy Steps, Go to command line
| 
| $ cd /root/path/to/your/framework
| if you have /tasks folder in modules root run
| $ php task.php tasks start
|  OR
| if you have a /tasks folder in a module run
| $ php task.php module task_controller method 'arguments' 
|
| 'Start.php' task controller located in your .modules/tasks path.
| Also you can use 'tasks' folder creating it under the current module.
|
| @author CJ Lazell
|
*/ 
if (isset($_SERVER['REMOTE_ADDR'])) exit('Access denied!');

/**
|--------------------------------------------------------------------------
| Cmd Constant
|--------------------------------------------------------------------------
| If CMD constant defined that means Obullo works in Command Line 
| mode so someone can check the mode using php defined() function.
|
*/  
define('CMD', 1);
 
/**
|--------------------------------------------------------------------------
| Get Command Line Arguments
|--------------------------------------------------------------------------
*/ 
unset($_SERVER['argv'][0]);

/**
|--------------------------------------------------------------------------
| We need to make sure is it Task or Cmd Request
| The main difference of a task request, user call it via
| task helper and run with task_run(); function internally.
|--------------------------------------------------------------------------
*/
if(end($_SERVER['argv']) == 'OB_TASK_REQUEST')
{
    define('TASK', 1);
}

/**
|--------------------------------------------------------------------------
| Set Command Line Arguments as Obullo Segments
|--------------------------------------------------------------------------
| Manually set the URI path based on command line arguments.
|
*/ 
$_SERVER['PATH_INFO']      = '/'. implode('/', $_SERVER['argv']) .'/';
$_SERVER['REQUEST_URI']    = $_SERVER['PATH_INFO'];
$_SERVER['QUERY_STRING']   = $_SERVER['PATH_INFO'];
$_SERVER['ORIG_PATH_INFO'] = $_SERVER['PATH_INFO'];


/**
|--------------------------------------------------------------------------
| Index.php file.
|--------------------------------------------------------------------------
| If you want to rename or move your general index.php also you need to
| to change it from here.
|
*/ 
require('index.php');