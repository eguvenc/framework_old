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
        'Router' => 'Http\Middlewares\Router',
        'Maintenance' => 'Http\Middlewares\Maintenance',
        'NotAllowed' => 'Http\Middlewares\NotAllowed',
        'NotFound' => 'Http\Middlewares\NotFound',
        // 'Auth' => 'Http\Middlewares\Auth',
        'Annotation' => 'Http\Middlewares\Annotation',
        // 'Guest' => 'Http\Middlewares\Guest',
        'View' => 'Http\Middlewares\View',
    ]
);
/*
|--------------------------------------------------------------------------
| Add your global middlewares
|--------------------------------------------------------------------------
*/
$c['middleware']->queue(
    [
        // 'Annotation',
        'Maintenance',
        'Router',
        // 'Error',
        'View',
    ]
);