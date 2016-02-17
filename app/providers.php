<?php
/*
|--------------------------------------------------------------------------
| Service Providers
|--------------------------------------------------------------------------
| Specifies your application components, services and service providers.
*/
$container->addServiceProvider('ServiceProvider\View');
$container->addServiceProvider('ServiceProvider\Url');
$container->addServiceProvider('ServiceProvider\Layer');
$container->addServiceProvider('ServiceProvider\Cache');
$container->addServiceProvider('Obullo\Container\ServiceProvider\Db');
$container->addServiceProvider('Obullo\Container\ServiceProvider\Logger');
$container->addServiceProvider('Obullo\Container\ServiceProvider\Translator');
// $container->addServiceProvider('Obullo\Container\ServiceProvider\Is');
// $container->addServiceProvider('Obullo\Container\ServiceProvider\Clean');
$container->addServiceProvider('Obullo\Container\ServiceProvider\Cookie');
$container->addServiceProvider('Obullo\Container\ServiceProvider\Session');
$container->addServiceProvider('Obullo\Container\ServiceProvider\Captcha');
// $container->addServiceProvider('ServiceProvider\ReCaptcha');
$container->addServiceProvider('Obullo\Container\ServiceProvider\Task');
$container->addServiceProvider('Obullo\Container\ServiceProvider\Form');
$container->addServiceProvider('Obullo\Container\ServiceProvider\Flash');
$container->addServiceProvider('Obullo\Container\ServiceProvider\Csrf');
$container->addServiceProvider('Obullo\Container\ServiceProvider\Element');
$container->addServiceProvider('Obullo\Container\ServiceProvider\Validator');
$container->addServiceProvider('Obullo\Container\ServiceProvider\User');
// $container->addServiceProvider('ServiceProvider\QueueAmqp');
// $container->addServiceProvider('ServiceProvider\QueryBuilder');

/*
|--------------------------------------------------------------------------
| Connectors
|--------------------------------------------------------------------------
| Specifies your connection managers.
*/
$container->addServiceProvider('ServiceProvider\Connector\Redis');
$container->addServiceProvider('ServiceProvider\Connector\CacheFactory');
$container->addServiceProvider('ServiceProvider\Connector\Memcached');
// $container->addServiceProvider('ServiceProvider\Connector\Memcache');
$container->addServiceProvider('ServiceProvider\Connector\Database');
$container->addServiceProvider('ServiceProvider\Connector\Amqp');
$container->addServiceProvider('ServiceProvider\Connector\Mongo');