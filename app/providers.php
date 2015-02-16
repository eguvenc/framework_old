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
$c->register(new Obullo\ServiceProviders\PdoServiceProvider);
/*
|--------------------------------------------------------------------------
| Database Service Provider
|--------------------------------------------------------------------------
*/
$c->register(new Obullo\ServiceProviders\DatabaseServiceProvider);
/*
|--------------------------------------------------------------------------
| Cache Service Provider
|--------------------------------------------------------------------------
*/
$c->register(new Obullo\ServiceProviders\CacheServiceProvider);
/*
|--------------------------------------------------------------------------
| AMQP Service Provider
|--------------------------------------------------------------------------
*/
$c->register(new Obullo\ServiceProviders\AMQPServiceProvider);