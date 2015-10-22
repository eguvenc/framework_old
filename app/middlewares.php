<?php
/*
|--------------------------------------------------------------------------
| Http Middlewares
|--------------------------------------------------------------------------
| Specifies the your application http middlewares
|
|  Default Middlewares
|
|   - Maintenance
|   - NotAllowed ( required )
|   - Begin  ( required )
|   - Finalize ( required )
|   - View
*/
/*
|--------------------------------------------------------------------------
| Middleware definitions
|--------------------------------------------------------------------------
*/
$c['middleware']->configure(
    [
        'NotFound' => 'Http\Middlewares\NotFound',
        // 'Auth' => 'Http\Middlewares\Auth',
        'Annotation' => 'Http\Middlewares\Annotation',
        'Begin' => 'Http\Middlewares\Begin',
        // 'Guest' => 'Http\Middlewares\Guest',
        'Maintenance' => 'Http\Middlewares\Maintenance',
        'NotAllowed' => 'Http\Middlewares\NotAllowed',
        'View' => 'Http\Middlewares\View',
    ]
);
/*
|--------------------------------------------------------------------------
| Add your global middlewares
|--------------------------------------------------------------------------
*/
$c['middleware']->add(
    [
        // 'NotAllowed',
        // 'Annotation',
        // 'NotFound',
        'View'
    ]
);