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
|       $autoload['helper'] = array('ob/view', 'ob/html', 'ob/url');
*/

$autoload['helper'] = array('ob/view', 'ob/html', 'ob/url');


/*
| -------------------------------------------------------------------
|  Auto-load Libraries
| -------------------------------------------------------------------
| These are the classes located in the obullo/libraries folder
| or in your application/libraries folder, 
| or modules/current_module/libraries folder.
| 
|
| Prototype:
|
|	$autoload['lib'] = array('ob/calendar');
|       $autoload['lib'] = array('app/mylib');
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
|	$autoload['config'] = array('config1', '');
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
|	$autoload['lang'] = array('langfilename', 'langfilename2');
|
*/

$autoload['lang']       = array();

/*
| -------------------------------------------------------------------
|  Auto-load Models
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['model'] = array('app/model1');
|
*/

$autoload['model']      = array();


/* End of file autoload.php */
/* Location: .app/config/autoload.php */