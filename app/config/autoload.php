<?php
defined('BASE') or exit('Access Denied!');
/*
| -------------------------------------------------------------------
| APPLICATION AUTO-LOADER
| -------------------------------------------------------------------
| This file specifies which APPLICATION file should be loaded by default.
|
| In order to keep the framework as light-weight as possible only the
| absolute minimal resources are loaded by default.This file lets
| you globally define which systems you would like loaded with every
| request.
|
*/

/*
| -------------------------------------------------------------------
|  Auto-load Helper Files
| -------------------------------------------------------------------
| Prototypes:
|
|       $autoload['helper']['ob/view']    = '';  // Value must be empty
|       $autoload['helper']['my_helper']  = '';
*/

$autoload['helper']['ob/view']  = '';
$autoload['helper']['ob/html']  = '';
$autoload['helper']['ob/url']   = '';


/*
| -------------------------------------------------------------------
|  Auto-load Libraries
| -------------------------------------------------------------------
| These are the classes located in the obullo/libraries folder
| or in your application/libraries folder, 
| or modules/current_module/libraries folder.
| Prototype:
|
|	$autoload['lib'] = array('ob/calendar', 'my_lib' => array('arg1', 'arg2'));
| 
| Prototype using arguments:
|
|       $autoload['lib']['app/mylib'] = array( array($construct_params), $object_name = 'string')); 
|
|       No Instantiate example
|
|       $autoload['lib']['app/anylib'] = array(FALSE);
| 
| 
| NOTE: Using libraries with FALSE means no instantiate 
| (e.g. like running loader::lib('app/my_library', false)) if you intend to use false 
| this will just include file, the library construct params must be array.
|
*/
$autoload['lib']        = array();

/*
| -------------------------------------------------------------------
|  Auto-load Config files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['config'] = array('config1' => '', 'config2' => '');
| 
| Prototype using arguments:
|
|       $autoload['config'] = array('config1' => array(TRUE), 'config2' => array($sections = TRUE));
|
| NOTE: This item is intended for use ONLY if you have created custom
| config files.  Otherwise, leave it blank.
|
*/

$autoload['config']     = array();

/*
| -------------------------------------------------------------------
|  Auto-load Language files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['lang'] = array('lang1' => '', 'lang2' => '');
| 
| Prototype using arguments:
|
|       $autoload['lang'] = array('lang1' => array('english'), 'lang2' => array('german', FALSE));
|
*/

$autoload['lang']       = array();

/*
| -------------------------------------------------------------------
|  Auto-load Models
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['model'] = array('model1' => '', 'model2' => array('arg1', 'arg2'));
| 
| Prototype using arguments:
|
|       $autoload['model'] = array('app/modelname' => array($object_name = 'string', array $construct_params)); 
|       $autoload['model']['app/modelname'] = array($param1, $param2); 
|
|       No Instantiate example
|
|       $autoload['model'] = array('app/modelname' => array(FALSE));   
|       $autoload['model']['app/modelname'] = array(FALSE);   
|
*/

$autoload['model']      = array();


/* End of file autoload.php */
/* Location: .app/config/autoload.php */