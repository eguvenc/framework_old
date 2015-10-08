<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

register_shutdown_function('fatal_handler');

function fatal_handler() {
  $errfile = "unknown file";
  $errstr  = "shutdown";
  $errno   = E_CORE_ERROR;
  $errline = 0;

  $error = error_get_last();
  if (isset($error['message'])) {
  	echo $error['message'];
  };
}

/*
|--------------------------------------------------------------------------
| Constants.
|--------------------------------------------------------------------------
*/
require 'constants';
/*
|--------------------------------------------------------------------------
| Autoloader
|--------------------------------------------------------------------------
*/
// require OBULLO .'Application/Autoloader.php';
/*
|--------------------------------------------------------------------------
| Register Autoloader
|--------------------------------------------------------------------------
*/
require 'vendor/autoload.php';

// Obullo\Application\Autoloader::register();
/*
|--------------------------------------------------------------------------
| Http Requests
|--------------------------------------------------------------------------
*/
require OBULLO .'Application/Http/Bootstrap.php';

/*
|--------------------------------------------------------------------------
| Initialize
|--------------------------------------------------------------------------
*/
$c['app']->run();