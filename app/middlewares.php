<?php
/*
|--------------------------------------------------------------------------
| Http Middlewares
|--------------------------------------------------------------------------
| Specifies the your application http middlewares
|
|  Default Middlewares
|
|   - Error ( Added by system if you use Zend Middleware )
|   - NotAllowed ( Added with the router )
|   - Router
|   - View ( Required for layouts )
*/
/*
|--------------------------------------------------------------------------
| Middleware definitions
|--------------------------------------------------------------------------
*/
$c['middleware']->register(
    [
        'Error' => 'Http\Middlewares\Error',
        'App' => 'Http\Middlewares\App',
        'TrustedIp' => 'Http\Middlewares\TrustedIp',
        'Csrf' => 'Http\Middlewares\Csrf',
        'ParsedBody' => 'Http\Middlewares\ParsedBody',
        'Debugger' => 'Http\Middlewares\Debugger',
        'Router' => 'Http\Middlewares\Router',
        'Maintenance' => 'Http\Middlewares\Maintenance',
        'NotAllowed' => 'Http\Middlewares\NotAllowed',
        'Auth' => 'Http\Middlewares\Auth',
        'Guest' => 'Http\Middlewares\Guest',
        'View' => 'Http\Middlewares\View',
    ]
);
/*
|--------------------------------------------------------------------------
| Add your global middlewares
|--------------------------------------------------------------------------
| Define router middleware at the top.
*/
$c['middleware']->add(
    [
        // 'Maintenance',
        // 'TrustedIp',
        // 'ParsedBody',
    
        'Router',
        // 'Csrf',
        'View',
        'App',
    ]
);