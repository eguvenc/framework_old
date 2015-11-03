<?php
/*
|--------------------------------------------------------------------------
| Http Middlewares
|--------------------------------------------------------------------------
| Specifies the your application http middlewares
|
|  Default Middlewares
|
|   - App ( Default added by system )
|   - Error ( Default added by system if you use Zend Stratigility )
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
        'App' => 'Http\Middlewares\App',
        'Error' => 'Http\Middlewares\Error',
        'Debugger' => 'Http\Middlewares\Debugger',
        'Router' => 'Http\Middlewares\Router',
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
        'View',
        // 'Annotation',
        // 'Maintenance',
    ]
);