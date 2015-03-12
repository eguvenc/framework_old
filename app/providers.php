<?php
/*
|--------------------------------------------------------------------------
| Service Providers
|--------------------------------------------------------------------------
| Specifies the your application service providers.
*/
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
| AMQP Service Provider
|--------------------------------------------------------------------------
*/
$c->register('Obullo\ServiceProviders\AMQPServiceProvider');