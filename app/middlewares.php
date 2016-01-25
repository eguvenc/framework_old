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
$middleware->register(
    [
        'Error' => 'Http\Middlewares\Error',
        'TrustedIp' => 'Http\Middlewares\TrustedIp',
        'ParsedBody' => 'Http\Middlewares\ParsedBody',
        'Debugger' => 'Http\Middlewares\Debugger',
        'Router' => 'Http\Middlewares\Router',
        'Maintenance' => 'Http\Middlewares\Maintenance',
        'NotAllowed' => 'Http\Middlewares\NotAllowed',
        'Translation' => 'Http\Middlewares\Translation',
        'Auth' => 'Http\Middlewares\Auth',
        'Https' => 'Http\Middlewares\Https',
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
$middleware->add(
    [
        // 'Maintenance',
        // 'TrustedIp',
        // 'ParsedBody',
        // 'Translation',
        'View',
        'Router',
    ]
);