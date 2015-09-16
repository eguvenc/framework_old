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
| Priority of providers very important database should be at the top.
|
*/
$c['app']->register(
    [
        'database' => 'Obullo\Service\Provider\Database',
        // 'database' => 'Obullo\Service\Provider\DoctrineDBAL',
        // 'qb' => 'Obullo\Service\Provider\DoctrineQueryBuilder',
        'cache' => 'Obullo\Service\Provider\Cache',
        'redis' => 'Obullo\Service\Provider\Redis',
        'memcached' => 'Obullo\Service\Provider\Memcached',
        'amqp' => 'Obullo\Service\Provider\Amqp',
        // 'amqp' => 'Obullo\Service\Provider\AmqpLib',
        'mongo' => 'Obullo\Service\Provider\Mongo',
    ]
);