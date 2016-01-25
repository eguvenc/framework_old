<?php
/*
|--------------------------------------------------------------------------
| Service Providers
|--------------------------------------------------------------------------
| Specifies your application components, services and service providers.
*/
$container->addServiceProvider('ServiceProvider\Logger');
$container->addServiceProvider('ServiceProvider\Translator');
// $container->addServiceProvider('ServiceProvider\Db');
$container->addServiceProvider('ServiceProvider\View');
$container->addServiceProvider('ServiceProvider\Cookie');
// $container->addServiceProvider('ServiceProvider\Csrf');
// $container->addServiceProvider('ServiceProvider\Captcha');
// $container->addServiceProvider('ServiceProvider\ReCaptcha');
// $container->addServiceProvider('ServiceProvider\Session');
// $container->addServiceProvider('ServiceProvider\Task');
$container->addServiceProvider('ServiceProvider\Url');
$container->addServiceProvider('ServiceProvider\Form');
$container->addServiceProvider('ServiceProvider\Validator');
// $container->addServiceProvider('ServiceProvider\User');
$container->addServiceProvider('ServiceProvider\Layer');
$container->addServiceProvider('ServiceProvider\Template');
// $container->addServiceProvider('ServiceProvider\QueueAmqp');
// $container->addServiceProvider('ServiceProvider\QueueAmqpLib');
// $container->addServiceProvider('ServiceProvider\DoctrineQueryBuilder');

/*
|--------------------------------------------------------------------------
| Connectors
|--------------------------------------------------------------------------
| Specifies your application service provider connectors.
*/
// $container->addServiceProvider('ServiceProvider\Database');
// // $container->addServiceProvider('ServiceProvider\DoctrineDBAL');
// $container->addServiceProvider('ServiceProvider\Redis');
// $container->addServiceProvider('ServiceProvider\Memcached');
// //  $container->addServiceProvider('ServiceProvider\Memcache');
// $container->addServiceProvider('ServiceProvider\Cache');
// $container->addServiceProvider('ServiceProvider\Amqp');
// // $container->addServiceProvider('ServiceProvider\AmqpLib');
// $container->addServiceProvider('ServiceProvider\Mongo');

// $container['app']->provider(
//     [
//         'database' => 'Obullo\Service\Providers\Database',
//         // 'database' => 'Obullo\Service\Providers\DoctrineDBAL',
//         'redis' => 'Obullo\Service\Providers\Redis',
//         'memcached' => 'Obullo\Service\Providers\Memcached',
//         // 'memcache' => 'Obullo\Service\Providers\Memcache',
//         'cache' => 'Obullo\Service\Providers\Cache',
//         'amqp' => 'Obullo\Service\Providers\Amqp',
//         // 'amqp' => 'Obullo\Service\Providers\AmqpLib',
//         'mongo' => 'Obullo\Service\Providers\Mongo'
//     ]
// );
/*
|--------------------------------------------------------------------------
| Components
|--------------------------------------------------------------------------
| Specifies your application services
*/
//     
// $container->share('element', 'Obullo\Form\Element')
//     ->withArgument($container)
//     ->withArgument($container->get('request'))
//     ->withArgument($container->get('config'))
//     ->withArgument($container->get('logger'));

// $container->share('template', 'Obullo\View\Template')
//     ->withArgument($container)
//     ->withArgument($container->get('view'))
//     ->withArgument($container->get('logger'));

// $container->share('flash', 'Obullo\Flash\Session')
//     ->withArgument($container->get('request'))
//     ->withArgument($container->get('config'))
//     ->withArgument($container->get('logger'))
//     ->withArgument($container->get('session'));

// $container->share('is', 'Obullo\Filters\Is');
// $container->share('clean', 'Obullo\Filters\Clean');
// $container->share('response', 'Obullo\Http\Response');