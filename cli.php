<?php
/*
|--------------------------------------------------------------------------
| Cli Requests
|--------------------------------------------------------------------------
*/
require OBULLO. 'Application/Cli.php';
require OBULLO .'Application/Autoloader.php';
/*
|--------------------------------------------------------------------------
| Autoloader
|--------------------------------------------------------------------------
*/
// require 'vendor/autoload.php';

Obullo\Application\Autoloader::register();
/*
|--------------------------------------------------------------------------
| Initialize
|--------------------------------------------------------------------------
*/
$c['app']->run();


/* End of file cli.php */
/* Location: .cli.php */