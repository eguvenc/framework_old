<?php
/*
|--------------------------------------------------------------------------
| Http Middlewares
|--------------------------------------------------------------------------
| Specifies the your application http middlewares
|
|  Default Middlewares
|
|   - Router ( required )
|   - Maintenance
|   - NotAllowed ( required )
|   - View
*/
/*
|--------------------------------------------------------------------------
| Middleware definitions
|--------------------------------------------------------------------------
*/
$c['middleware']->configure(
    [
        'Error' => 'Http\Middlewares\Error',
        'Debugger' => 'Http\Middlewares\Debugger',
        'Router' => 'Http\Middlewares\Router',
        'Call' => 'Http\Middlewares\Call',
        'Maintenance' => 'Http\Middlewares\Maintenance',
        'NotAllowed' => 'Http\Middlewares\NotAllowed',
        'Auth' => 'Http\Middlewares\Auth',
        'Annotation' => 'Http\Middlewares\Annotation',
        'Guest' => 'Http\Middlewares\Guest',
        'View' => 'Http\Middlewares\View',
    ]
);
/*
|--------------------------------------------------------------------------
| Add your global middlewares
|--------------------------------------------------------------------------
| Warning ! : Router & Annotaion middleware must defined at the top.
*/
$c['middleware']->queue(
    [
        'Router',
        'Call',
        // 'Annotation',
        // 'Maintenance',
        // 'Error',
        'View',
    ]
);