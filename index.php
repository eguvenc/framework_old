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
| Create App with Zend Diactoros
|--------------------------------------------------------------------------
*/
$server = Obullo\Http\Server::createServer(
    function ($request, $response, $done) use ($c) {

        $relay = new Relay\RelayBuilder;
        $dispatcher = $relay->newInstance(
            $c['middleware']->getValues()
        );
        $response = $dispatcher($request, $response);
        return $response;
    },
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);
/*
|--------------------------------------------------------------------------
| Run
|--------------------------------------------------------------------------
*/
$server->listen();