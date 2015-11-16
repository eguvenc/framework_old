<?php
/*
|--------------------------------------------------------------------------
| Http Middlewares
|--------------------------------------------------------------------------
| Specifies the your application http middlewares
|
|  Default Middlewares
|
|   - App ( Required by system )
|   - Error ( Added by system if you use Zend Middleware )
|   - NotAllowed ( Added with the router )
|   - Router
|   - View
*/
/*
|--------------------------------------------------------------------------
| Middleware definitions
|--------------------------------------------------------------------------
*/
$c['middleware']->configure(
    [
        'App' => 'Http\Middlewares\App',
        'Error' => 'Http\Middlewares\Error',
        'TrustedIp' => 'Http\Middlewares\TrustedIp',
        'Debugger' => 'Http\Middlewares\Debugger',
        'Router' => 'Http\Middlewares\Router',
        'Maintenance' => 'Http\Middlewares\Maintenance',
        'NotAllowed' => 'Http\Middlewares\NotAllowed',
        'Annotation' => 'Http\Middlewares\Annotation',
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
$c['middleware']->queue(
    [
        // 'Maintenance',
        // 'TrustedIp',
        'App',
        'Router',
        'View',
    ]
);