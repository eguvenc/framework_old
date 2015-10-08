<?php
/*
|--------------------------------------------------------------------------
| Components
|--------------------------------------------------------------------------
| Specifies the your application components, services and service providers 
| which they available by default.
*/

$c['app']->provider(
    [
        'database' => 'Obullo\Service\Provider\Database',
        // 'database' => 'Obullo\Service\Provider\DoctrineDBAL',
        // 'qb' => 'Obullo\Service\Provider\DoctrineQueryBuilder',
        'redis' => 'Obullo\Service\Provider\Redis',
        'memcached' => 'Obullo\Service\Provider\Memcached',
        // 'memcache' => 'Obullo\Service\Provider\Memcache',
        'amqp' => 'Obullo\Service\Provider\Amqp',
        // 'amqp' => 'Obullo\Service\Provider\AmqpLib',
        'mongo' => 'Obullo\Service\Provider\Mongo',
    ]
)->service(
    [
        'logger' => 'Obullo\Log\LogManager',
        'cache' => 'Obullo\Cache\CacheManager',
    ]
)->component(
    [
        'event' => 'Obullo\Event\Event',
        'exception' => 'Obullo\Error\Exception',
        'error' => 'Obullo\Http\Error',
        'output' => 'Obullo\Http\Output',
        'translator' => 'Obullo\Translation\Translator',
        'cookie' => 'Obullo\Cookie\Cookie',
        'is' => 'Obullo\Filters\Is',
        'clean' => 'Obullo\Filters\Clean',
        'agent' => 'Obullo\Http\UserAgent',
        'layer' => 'Obullo\Layer\Request',
        'url' => 'Obullo\Url\Url',
        'task' => 'Obullo\Cli\Task\Task',
        'router' => 'Obullo\Router\Router',
        'flash' => 'Obullo\Flash\Session',
        'form' => 'Obullo\Form\Form',
        'element' => 'Obullo\Form\Element',
        'password' => 'Obullo\Crypt\Password\Bcrypt',
        'csrf' => 'Obullo\Security\Csrf',
        'validator' => 'Obullo\Validator\Validator',
        'view' => 'Obullo\View\View',
    ]
)->dependency(
    [
        'app',
        'config',
        'logger',
        'uri',
        'cache',
        'request',
        'response',
    ]
);