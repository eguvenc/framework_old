<?php

register_shutdown_function('shutdownFunction');

function shutDownFunction() { 
    $error = error_get_last();
    // fatal error, E_ERROR === 1
    if (! empty($error)) { 
		var_dump($error);
    } 
}

/*
|--------------------------------------------------------------------------
| Disable php.ini errors to use set_error_handler() func
|--------------------------------------------------------------------------
*/
ini_set('display_errors', 1);
/*
|--------------------------------------------------------------------------
| Disable all php errors to use set_error_handler() func
|--------------------------------------------------------------------------
*/
error_reporting(1);
/*
|--------------------------------------------------------------------------
| Constants.
|--------------------------------------------------------------------------
*/
require '../constants';
/*
|--------------------------------------------------------------------------
| Register Autoloader
|--------------------------------------------------------------------------
*/
require '../vendor/autoload.php';


require OBULLO .'Application/Autoloader.php';
Obullo\Application\Autoloader::register();

/*
|--------------------------------------------------------------------------
| Set timezone identifier
|--------------------------------------------------------------------------
*/
date_default_timezone_set('Europe/London');
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
// $app = new Obullo\Http\Relay\MiddlewarePipe($c);
$app = new Obullo\Http\Zend\Stratigility\MiddlewarePipe($c);
/*
|--------------------------------------------------------------------------
| Create your http server
|--------------------------------------------------------------------------
*/
$server = Obullo\Http\Zend\Diactoros\Server::createServerFromRequest(
    $app,
    Obullo\Log\Benchmark::start($app->getRequest())
);
/*
|--------------------------------------------------------------------------
| Run
|--------------------------------------------------------------------------
*/
$server->listen();