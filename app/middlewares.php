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
|   - NotAllowed
|   - Begin
|   - Finalize
*/
/*
|--------------------------------------------------------------------------
| Middleware definitions
|--------------------------------------------------------------------------
*/
$c['middleware']->configure(
    [
        // 'Auth' => 'Http\Middlewares\Auth',
        // 'Guest' => 'Http\Middlewares\Guest',
        'Maintenance' => 'Http\Middlewares\Maintenance',
        'NotAllowed' => 'Http\Middlewares\NotAllowed',
        'Begin' => 'Http\Middlewares\Begin',
        'Finalize' => 'Http\Middlewares\Finalize',
    ]
);
/*
|--------------------------------------------------------------------------
| Add your global middlewares
|--------------------------------------------------------------------------
*/
$c['middleware']->add(
    [
        'NotAllowed',
    ]
);