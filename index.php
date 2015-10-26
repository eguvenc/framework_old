<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

register_shutdown_function('fatal_handler');
function fatal_handler() {
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
| Bootstrap
|--------------------------------------------------------------------------
*/
require OBULLO .'Application/Http/Bootstrap.php';
/*
|--------------------------------------------------------------------------
| Create Zend Stratigility
|--------------------------------------------------------------------------
*/
$app = new Obullo\Zend\Stratigility\MiddlewarePipe($c['app']->env());
/*
|--------------------------------------------------------------------------
| Create Attached Middlewares
|--------------------------------------------------------------------------
*/
foreach ($c['middleware']->getValues() as $middleware) {
    $app->pipe($middleware);
}
/*
|--------------------------------------------------------------------------
| Create Zend Diactoros App
|--------------------------------------------------------------------------
*/
$server = Obullo\Http\Server::createServerFromRequest(
    $app,
    $c['request'],
    $c['response']
);
/*
|--------------------------------------------------------------------------
| Run
|--------------------------------------------------------------------------
*/
$server->listen();