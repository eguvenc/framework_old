<?php
/*
|--------------------------------------------------------------------------
| Components
|--------------------------------------------------------------------------
| Specifies your application components, services, providers and dependencies.
*/
$c['app']->provider(
    [
        'database' => 'Obullo\Service\Provider\Database',
        // 'database' => 'Obullo\Service\Provider\DoctrineDBAL',
        // 'qb' => 'Obullo\Service\Provider\DoctrineQueryBuilder',
        'redis' => 'Obullo\Service\Provider\Redis',
        'memcached' => 'Obullo\Service\Provider\Memcached',
        // 'memcache' => 'Obullo\Service\Provider\Memcache',
        'cache' => 'Obullo\Service\Provider\Cache',
        'amqp' => 'Obullo\Service\Provider\Amqp',
        // 'amqp' => 'Obullo\Service\Provider\AmqpLib',
        'mongo' => 'Obullo\Service\Provider\Mongo'
    ]
);

$c['app']->service(
    [
        'logger' => 'Obullo\Log\LogManager',
        'layer' => 'Obullo\Layer\LayerManager',
        'url' => 'Obullo\Url\UrlManager',
        'db' => 'Obullo\Database\DatabaseManager',
        'session' => 'Obullo\Session\SessionManager',
        'queue' => 'Obullo\Queue\QueueManagerAmqp',
        'user' => 'Obullo\Authentication\AuthManager'
        // 'queue' => 'Obullo\Queue\QueueManagerAmqpLib',
    ]
);

$c['app']->component(
    [
        'response' => 'Obullo\Http\Response',
        'event' => 'Obullo\Event\Event',
        'middleware' => 'Obullo\Application\Middleware',
        'translator' => 'Obullo\Translation\Translator',
        'cookie' => 'Obullo\Cookie\Cookie',
        'is' => 'Obullo\Filters\Is',
        'clean' => 'Obullo\Filters\Clean',
        'task' => 'Obullo\Cli\Task\Task',
        'router' => 'Obullo\Router\Router',
        'flash' => 'Obullo\Flash\Session',
        'form' => 'Obullo\Form\Form',
        'element' => 'Obullo\Form\Element',
        'password' => 'Obullo\Crypt\Password\Bcrypt',
        'csrf' => 'Obullo\Security\Csrf',
        'validator' => 'Obullo\Validator\Validator',
        'view' => 'Obullo\View\View',
        'template' => 'Obullo\View\Template',
    ]
);

$c['app']->dependency(
    [
        'dependency',
        'app',
        'middleware',
        'config',
        'layer',
        'logger',
        'request',
        'response',
        'session',
        'queue',
        'user',
    ]
);
