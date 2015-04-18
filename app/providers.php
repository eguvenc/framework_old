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
$c->register('Obullo\ServiceProviders\LoggerServiceProvider');
/*
|--------------------------------------------------------------------------
| Pdo Service Provider
|--------------------------------------------------------------------------
*/
$c->register('Obullo\ServiceProviders\PdoServiceProvider');
/*
|--------------------------------------------------------------------------
| Database Service Provider
|--------------------------------------------------------------------------
*/
$c->register('Obullo\ServiceProviders\DatabaseServiceProvider');
/*
|--------------------------------------------------------------------------
| Cache Service Provider
|--------------------------------------------------------------------------
*/
$c->register('Obullo\ServiceProviders\CacheServiceProvider');
/*
|--------------------------------------------------------------------------
| Redis Service Provider
|--------------------------------------------------------------------------
*/
$c->register('Obullo\ServiceProviders\RedisServiceProvider');
/*
|--------------------------------------------------------------------------
| Memcached Service Provider
|--------------------------------------------------------------------------
*/
$c->register('Obullo\ServiceProviders\MemcachedServiceProvider');
/*
|--------------------------------------------------------------------------
| Mailer Service Provider
|--------------------------------------------------------------------------
*/
$c->register('Obullo\ServiceProviders\MailerServiceProvider');
/*
|--------------------------------------------------------------------------
| AMQP Service Provider
|--------------------------------------------------------------------------
*/
$c->register('Obullo\ServiceProviders\AMQPServiceProvider');
/*
|--------------------------------------------------------------------------
| Query Builder Service Provider
|--------------------------------------------------------------------------
*/
$c->register('Obullo\ServiceProviders\QueryServiceProvider');


/* End of file providers.php */
/* Location: .app/providers.php */