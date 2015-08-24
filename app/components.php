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
        'error' => 'Obullo\Http\Error',
        'translator' => 'Obullo\Translation\Translator',
        'cookie' => 'Obullo\Cookie\Cookie',
        'request' => 'Obullo\Http\Request',
        'response' => 'Obullo\Http\Response',
        'is' => 'Obullo\Filters\Is',
        'clean' => 'Obullo\Filters\Clean',
        'agent' => 'Obullo\Http\UserAgent',
        'layer' => 'Obullo\Layer\Request',
        'uri' => 'Obullo\Uri\Uri',
        'url' => 'Obullo\Url\Url',
        'cli' => 'Obullo\Cli\Cli',
        'task' => 'Obullo\Task\Task',
        'router' => 'Obullo\Router\Router',
        'flash' => 'Obullo\Flash\Session',
        'form' => 'Obullo\Form\Form',
        'element' => 'Obullo\Form\Element',
        'password' => 'Obullo\Crypt\Password\Bcrypt',
        'csrf' => 'Obullo\Security\Csrf',
        'validator' => 'Obullo\Validator\Validator',
        'view' => 'Obullo\View\View'
    ]
);