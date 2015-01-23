<?php
/*
|--------------------------------------------------------------------------
| Constants.
|--------------------------------------------------------------------------
*/
require 'constants';
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
| Http Bootstrap
|--------------------------------------------------------------------------
*/
require OBULLO_CONTAINER;
require OBULLO_AUTOLOADER;
require OBULLO_CORE;
require OBULLO_HTTP;


/* End of file index.php */
/* Location: .index.php */