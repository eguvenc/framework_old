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
|   - Benchmark
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
        'Benchmark' => 'Http\Middlewares\Benchmark',
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
| Warning ! : Set Benchmark middleware at index "0" of array.
*/
$c['middleware']->add(
    [
        'Router',
        'Benchmark',
        // 'Annotation',
        'View',
    ]
);