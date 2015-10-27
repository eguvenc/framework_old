

Obullo Setup
===

When you setup your application you have two options to work with Http middlewares.

### Setup with Relay ( Default )

It's a simple and lightweight middleware solution.

```php
composer require relay/relay
```

Open your index.php and update <kbd>$app</kbd> variable as Http\Relay;

```php
/*
|--------------------------------------------------------------------------
| Choose your middleware app
|--------------------------------------------------------------------------
*/
$app = new Obullo\Http\Relay($c);
/*
|--------------------------------------------------------------------------
| Create your http server
|--------------------------------------------------------------------------
*/
$server = Obullo\Http\Server::createServerFromRequest(
    $app,
    $c['request'],
    $c['response']
);
/*
|--------------------------------------------------------------------------
| Run
|--------------------------------------------------------------------------
*/
$server->listen();
```

Learn more details about <a href="http://relayphp.com/" target="_blank">relay middleware</a>.


### Setup with Zend Stratigility

It's a advanced middleware solution from zend.

Open your index.php and update <kbd>$app</kbd> variable as Http\Zend\Stratigility\MiddlewarePipe;

```php
/*
|--------------------------------------------------------------------------
| Choose your middleware app
|--------------------------------------------------------------------------
*/
$app = new Obullo\Http\Zend\Stratigility\MiddlewarePipe($c);
```

Learn more details about <a href="https://github.com/zendframework/zend-stratigility" target="_blank">zend middleware</a>.