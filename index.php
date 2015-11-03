<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// register_shutdown_function('fatal_handler');
// function fatal_handler() {
//   $error = error_get_last();
//   if (isset($error['message'])) {
//     echo $error['message'];
//   };
// }
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
| Bootstrap
|--------------------------------------------------------------------------
*/
require OBULLO .'Application/Http/Bootstrap.php';
/*
|--------------------------------------------------------------------------
| Choose your middleware app
|--------------------------------------------------------------------------
*/
// $app = new Obullo\Http\Relay($c);
$app = new Obullo\Http\Zend\Stratigility\MiddlewarePipe($c);
/*
|--------------------------------------------------------------------------
| Create your http server
|--------------------------------------------------------------------------
*/
$server = Obullo\Http\Zend\Diactoros\Server::createServerFromRequest(
    $app,
    $app->getRequest()
);
/*
|--------------------------------------------------------------------------
| Run
|--------------------------------------------------------------------------
*/
$server->listen();