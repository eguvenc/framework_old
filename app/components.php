<?php
/*
|--------------------------------------------------------------------------
| Components
|--------------------------------------------------------------------------
| Specifies your application components, services, providers and dependencies.
*/
$c['app']->provider(
    [
        'database' => 'Obullo\Service\Providers\Database',
        // 'database' => 'Obullo\Service\Providers\DoctrineDBAL',
        // 'qb' => 'Obullo\Service\Providers\DoctrineQueryBuilder',
        'redis' => 'Obullo\Service\Providers\Redis',
        'memcached' => 'Obullo\Service\Providers\Memcached',
        // 'memcache' => 'Obullo\Service\Providers\Memcache',
        'cache' => 'Obullo\Service\Providers\Cache',
        'amqp' => 'Obullo\Service\Providers\Amqp',
        // 'amqp' => 'Obullo\Service\Providers\AmqpLib',
        'mongo' => 'Obullo\Service\Providers\Mongo'
    ]
);

$c['app']->service(
    [
        'logger' => 'Log\LogManager',
        'db' => 'Obullo\Database\DatabaseManager',
        'url' => 'Obullo\Url\UrlManager',
        'csrf' => 'Obullo\Security\CsrfManager',
        'user' => 'Obullo\Authentication\AuthManager',
        'layer' => 'Obullo\Layer\LayerManager',
        'queue' => 'Obullo\Queue\QueueManagerAmqp',
        // 'queue' => 'Obullo\Queue\QueueManagerAmqpLib',
        'session' => 'Obullo\Session\SessionManager',
        'captcha' => 'Obullo\Captcha\CaptchaManager',
    ]
);

$c['app']->component(
    [
        'view' => 'Obullo\View\View',
        'is' => 'Obullo\Filters\Is',
        'task' => 'Obullo\Cli\Task\Task',
        'form' => 'Obullo\Form\Form',
        'clean' => 'Obullo\Filters\Clean',
        'flash' => 'Obullo\Flash\Session',
        'router' => 'Obullo\Router\Router',
        'cookie' => 'Obullo\Cookie\Cookie',
        'element' => 'Obullo\Form\Element',
        'template' => 'Obullo\View\Template',
        'response' => 'Obullo\Http\Response',
        'validator' => 'Obullo\Validator\Validator',
        'middleware' => 'Obullo\Application\MiddlewareStack',
        'translator' => 'Obullo\Translation\Translator',
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
