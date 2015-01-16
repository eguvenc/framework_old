<?php
/*
|--------------------------------------------------------------------------
| Php startup error handler
|--------------------------------------------------------------------------
*/
if (error_get_last() != null) {
    include APP .'templates'. DS .'errors'. DS .'startup.php';
}
/*
|--------------------------------------------------------------------------
| Cli Bootstrap
|--------------------------------------------------------------------------
*/
require OBULLO_CONTAINER;
require OBULLO_AUTOLOADER;
require OBULLO_CORE;
require OBULLO_CONTROLLER;
require OBULLO_COMPONENTS;
require OBULLO_EVENTS;
require OBULLO_ROUTES;
/*
|--------------------------------------------------------------------------
| Initialize Routes
|--------------------------------------------------------------------------
*/
$c['router']->init();

require OBULLO_FILTERS;
require OBULLO_CLI;


/* End of file index.php */
/* Location: .index.php */