<?php
/*
|--------------------------------------------------------------------------
| Constants.
|--------------------------------------------------------------------------
*/
if ( ! defined('ROOT')) {  // Cli support
    include 'constants';
}
/*
|--------------------------------------------------------------------------
| Php Errors ( Default Off ) 
|--------------------------------------------------------------------------
| For security reasons its default off.
|
*/
// error_reporting(E_ALL);  // We will remove it this for just core tests.
// ini_set('display_errors', 1);			   // this will removed in new version, config['debug'] option control this feature.
// ini_set('error_reporting', 1);
/*
|--------------------------------------------------------------------------
| Set Default Time Zone Identifer. @link http://www.php.net/manual/en/timezones.php
|--------------------------------------------------------------------------                                        
| Set the default timezone identifier for date function ( Server Time ).
|
*/
date_default_timezone_set('Europe/London');
/*
|--------------------------------------------------------------------------
| Bootstrap
|--------------------------------------------------------------------------
*/
require OBULLO_CONTAINER;
require OBULLO_AUTOLOADER;
require OBULLO_CORE;
require OBULLO_CONTROLLER;
require OBULLO_COMPONENTS;
require OBULLO_FILTERS;
require OBULLO_PHP;


/* End of file index.php */
/* Location: .index.php */