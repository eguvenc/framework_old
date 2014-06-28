<?php

if (isset($_SERVER['REMOTE_ADDR'])) die('Access denied');
/*
|--------------------------------------------------------------------------
| Set Headers
|--------------------------------------------------------------------------
| Prevent header errors
|
*/
$_SERVER['HTTP_USER_AGENT']     = 'Cli';
$_SERVER['HTTP_ACCEPT_CHARSET'] = 'utf-8';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
| This file specifies which APP constants should be loaded by default.
|
*/
require 'constants';
require OBULLO_CONTAINER;
require OBULLO_AUTOLOADER;
require OBULLO_CORE;
require OBULLO_CONTROLLER;
require OBULLO_PROVIDERS;
require OBULLO_SERVICES;
require OBULLO_COMPONENTS;
require OBULLO_FILTERS;
require OBULLO_PHP;
/*
|--------------------------------------------------------------------------
| Hello Cli Task
|--------------------------------------------------------------------------
*/

echo "Hello World !\n\n";


/* End of file logger.php */
/* Location: .app/tasks/cli/welcome/start.php */