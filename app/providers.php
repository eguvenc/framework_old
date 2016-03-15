<?php
/*
|--------------------------------------------------------------------------
| Service Providers
|--------------------------------------------------------------------------
| Specifies your application components, services and service providers.
*/
$container->addServiceProvider('ServiceProvider\Url');
$container->addServiceProvider('ServiceProvider\View');
$container->addServiceProvider('ServiceProvider\Layer');
$container->addServiceProvider('ServiceProvider\Cache');
$container->addServiceProvider('ServiceProvider\Captcha');
// $container->addServiceProvider('ServiceProvider\Queue');
$container->addServiceProvider('Obullo\Container\ServiceProvider\Db');
$container->addServiceProvider('Obullo\Container\ServiceProvider\Logger');
$container->addServiceProvider('Obullo\Container\ServiceProvider\Translator');
// $container->addServiceProvider('Obullo\Container\ServiceProvider\Is');
// $container->addServiceProvider('Obullo\Container\ServiceProvider\Clean');
$container->addServiceProvider('Obullo\Container\ServiceProvider\Cookie');
$container->addServiceProvider('Obullo\Container\ServiceProvider\Session');
// $container->addServiceProvider('Obullo\Container\ServiceProvider\ReCaptcha');
$container->addServiceProvider('Obullo\Container\ServiceProvider\Task');
$container->addServiceProvider('Obullo\Container\ServiceProvider\Form');
$container->addServiceProvider('Obullo\Container\ServiceProvider\Flash');
$container->addServiceProvider('Obullo\Container\ServiceProvider\Csrf');
$container->addServiceProvider('Obullo\Container\ServiceProvider\Validator');
$container->addServiceProvider('Obullo\Container\ServiceProvider\Password');
$container->addServiceProvider('Obullo\Container\ServiceProvider\User');
// $container->addServiceProvider('Obullo\Container\ServiceProvider\QueryBuilder');

/*
|--------------------------------------------------------------------------
| Connectors
|--------------------------------------------------------------------------
| Specifies your connection managers.
*/
$container->addServiceProvider('ServiceProvider\Amqp');
$container->addServiceProvider('ServiceProvider\Database');
$container->addServiceProvider('ServiceProvider\Redis');
$container->addServiceProvider('ServiceProvider\Memcached');
$container->addServiceProvider('ServiceProvider\Mongo');