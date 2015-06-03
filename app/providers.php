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
        'logger' => 'Obullo\Service\Providers\LoggerServiceProvider',
        'database' => 'Obullo\Service\Providers\DatabaseServiceProvider',
        // 'database' => 'Obullo\Service\Providers\DoctrineDBALServiceProvider',
        'qb' => 'Obullo\Service\Providers\DoctrineQueryBuilderServiceProvider',
        'cache' => 'Obullo\Service\Providers\CacheServiceProvider',
        'redis' => 'Obullo\Service\Providers\RedisServiceProvider',
        'memcached' => 'Obullo\Service\Providers\MemcachedServiceProvider',
        'mailer' => 'Obullo\Service\Providers\MailerServiceProvider',
        'amqp' => 'Obullo\Service\Providers\AmqpServiceProvider',
    ]
);

/* End of file providers.php */
/* Location: .app/providers.php */