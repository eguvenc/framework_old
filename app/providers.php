<?php
/*
|--------------------------------------------------------------------------
| Service Providers
|--------------------------------------------------------------------------
| Specifies the your application service providers.
*/
/*
|--------------------------------------------------------------------------
| Register application service providers
|--------------------------------------------------------------------------
| Priority of providers very important forexample logger provider must be register
| at the top.
|
*/
$c['app']->register(
    [
        'database' => 'Obullo\Service\Providers\Database',
        // 'database' => 'Obullo\Service\Providers\DoctrineDBAL',
        // 'qb' => 'Obullo\Service\Providers\DoctrineQueryBuilder',
        'cache' => 'Obullo\Service\Providers\Cache',
        'redis' => 'Obullo\Service\Providers\Redis',
        'memcached' => 'Obullo\Service\Providers\Memcached',
        'amqp' => 'Obullo\Service\Providers\Amqp',
        // 'amqp' => 'Obullo\Service\Providers\AmqpLib',
        'mongo' => 'Obullo\Service\Providers\Mongo',
    ]
);