<?php
/*
|--------------------------------------------------------------------------
| Http Middlewares
|--------------------------------------------------------------------------
| Specifies the your application http middlewares
|
|  Default Middlewares
|
|   - Error ( System )
|   - NotAllowed ( System )
|   - Router
|   - View ( Required for Layers )
*/
/*
|--------------------------------------------------------------------------
| Register all middlewares
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
        'RewriteLocale' => 'Http\Middlewares\RewriteLocale',
        'Auth' => 'Http\Middlewares\Auth',
        'Https' => 'Http\Middlewares\Https',
        'Guest' => 'Http\Middlewares\Guest',
        'View' => 'Http\Middlewares\View',
    ]
);
/*
|--------------------------------------------------------------------------
| Global middlewares
|--------------------------------------------------------------------------
| Define router middleware at the top.
*/
$middleware->init(
    [
        // 'Maintenance',
        // 'TrustedIp',
        // 'ParsedBody',
        // 'Translation',
        // 'View',
        'Router',
    ]
);