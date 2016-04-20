<?php
/*
|--------------------------------------------------------------------------
| Http Middlewares
|--------------------------------------------------------------------------
| Specifies the your application http middlewares
|
|  Default Middlewares
|
|   - Error
|   - NotAllowed ( System )
*/
/*
|--------------------------------------------------------------------------
| Global middlewares
|--------------------------------------------------------------------------
| Define router middleware at the top.
*/
$middleware->add(
    [
        'Router',
        'App',
        // 'Maintenance',
        // 'TrustedIp',
        // 'ParsedBody',
        // 'Translation',
    ]
);