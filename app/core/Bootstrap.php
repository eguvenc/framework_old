<?php
defined('BASE') or exit('Access Denied!');
/**
|--------------------------------------------------------------------------
| User Front Controller for Bootstrap.php file.
|--------------------------------------------------------------------------
| User can create own Front Controller who want replace
| system methods by overriding to Bootstrap.php library.
|
| @see User Guide: Chapters / General Topics / Control Your Application Boot
|
*/

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

// Put your functions here ..
 

// END Bootstrap.php File

/* End of file Bootstrap.php */
/* Location: ./application/core/Bootstrap.php */