<?php
/*
|--------------------------------------------------------------------------
| Components
|--------------------------------------------------------------------------
| Specifies the your application components which they available by default.
*/
/*
|--------------------------------------------------------------------------
| Register core components
|--------------------------------------------------------------------------
*/
$c['app']->component(
    [
        'event' => 'Obullo\Event\Event',
        'exception' => 'Obullo\Error\Exception',
        'translator' => 'Obullo\Translation\Translator',
        'request' => 'Obullo\Http\Request',
        'response' => 'Obullo\Http\Response',
        'is' => 'Obullo\Http\Filters\Is',
        'clean' => 'Obullo\Http\Filters\Clean',
        'agent' => 'Obullo\Http\UserAgent',
        'layer' => 'Obullo\Layer\Request',
        'uri' => 'Obullo\Uri\Uri',
        'router' => 'Obullo\Router\Router',
    ]
);