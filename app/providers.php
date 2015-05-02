<?php
/*
|--------------------------------------------------------------------------
| Service Providers
|--------------------------------------------------------------------------
| Specifies the your application service providers.
*/
/*
|--------------------------------------------------------------------------
| Logger Service Provider
|--------------------------------------------------------------------------
*/
$c['app']->register('Obullo\Service\Providers\LoggerServiceProvider');
/*
|--------------------------------------------------------------------------
| Pdo Service Provider
|--------------------------------------------------------------------------
*/
$c['app']->register('Obullo\Service\Providers\PdoServiceProvider');
/*
|--------------------------------------------------------------------------
| Database Service Provider
|--------------------------------------------------------------------------
*/
$c['app']->register('Obullo\Service\Providers\DatabaseServiceProvider');
/*
|--------------------------------------------------------------------------
| Cache Service Provider
|--------------------------------------------------------------------------
*/
$c['app']->register('Obullo\Service\Providers\CacheServiceProvider');
/*
|--------------------------------------------------------------------------
| Redis Service Provider
|--------------------------------------------------------------------------
*/
$c['app']->register('Obullo\Service\Providers\RedisServiceProvider');
/*
|--------------------------------------------------------------------------
| Memcached Service Provider
|--------------------------------------------------------------------------
*/
$c['app']->register('Obullo\Service\Providers\MemcachedServiceProvider');
/*
|--------------------------------------------------------------------------
| Mailer Service Provider
|--------------------------------------------------------------------------
*/
$c['app']->register('Obullo\Service\Providers\MailerServiceProvider');
/*
|--------------------------------------------------------------------------
| AMQP Service Provider
|--------------------------------------------------------------------------
*/
$c['app']->register('Obullo\Service\Providers\AmqpServiceProvider');
/*
|--------------------------------------------------------------------------
| Query Builder Service Provider
|--------------------------------------------------------------------------
*/
$c['app']->register('Obullo\Service\Providers\QueryServiceProvider');


/* End of file providers.php */
/* Location: .app/providers.php */