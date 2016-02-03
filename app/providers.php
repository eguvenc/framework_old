<?php
/*
|--------------------------------------------------------------------------
| Service Providers
|--------------------------------------------------------------------------
| Specifies your application components, services and service providers.
*/
$container->addServiceProvider('ServiceProvider\Logger');
$container->addServiceProvider('ServiceProvider\Translator');
// $container->addServiceProvider('ServiceProvider\Is');
// $container->addServiceProvider('ServiceProvider\Clean');
$container->addServiceProvider('ServiceProvider\View');
$container->addServiceProvider('ServiceProvider\Cookie');
$container->addServiceProvider('ServiceProvider\Session');
$container->addServiceProvider('ServiceProvider\Captcha');
// $container->addServiceProvider('ServiceProvider\ReCaptcha');
// $container->addServiceProvider('ServiceProvider\Task');
$container->addServiceProvider('ServiceProvider\Url');
$container->addServiceProvider('ServiceProvider\Form');
$container->addServiceProvider('ServiceProvider\Flash');
// $container->addServiceProvider('ServiceProvider\Csrf');
$container->addServiceProvider('ServiceProvider\Element');
$container->addServiceProvider('ServiceProvider\Validator');
$container->addServiceProvider('ServiceProvider\Layer');
$container->addServiceProvider('ServiceProvider\View');
$container->addServiceProvider('ServiceProvider\User');
// $container->addServiceProvider('ServiceProvider\QueueAmqp');
// $container->addServiceProvider('ServiceProvider\QueryBuilder');

/*
|--------------------------------------------------------------------------
| Connectors
|--------------------------------------------------------------------------
| Specifies your connection managers.
*/
$container->addServiceProvider('ServiceProvider\Connector\Cache');
$container->addServiceProvider('ServiceProvider\Connector\Redis');
// $container->addServiceProvider('ServiceProvider\Connector\Memcached');
// $container->addServiceProvider('ServiceProvider\Connector\Memcache');
$container->addServiceProvider('ServiceProvider\Connector\Database');
$container->addServiceProvider('ServiceProvider\Connector\Amqp');
$container->addServiceProvider('ServiceProvider\Connector\Mongo');