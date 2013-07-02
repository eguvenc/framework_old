<?php

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

$autoload['helper']     = array('ob/view', 'ob/html', 'ob/url');

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
|	$autoload['locale'] = array('langfilename', 'langfilename2');
|
*/

$autoload['locale']     = array();

/*
| -------------------------------------------------------------------
|  Auto-load Models
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['model'] = array('model', '../model');
|
*/

$autoload['model']      = array();


/* End of file autoload.php */
/* Location: .app/config/autoload.php */