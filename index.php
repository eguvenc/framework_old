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
require OBULLO_GLOBAL;
require OBULLO_ROUTES;

/*
|--------------------------------------------------------------------------
| Initialize Routes
|--------------------------------------------------------------------------
*/
$c['router']->init();

require OBULLO_FILTERS;
require OBULLO_PHP;


/* End of file index.php */
/* Location: .index.php */