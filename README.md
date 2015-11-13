
### Status

----

There is no release yet, we are still working on it.

### Installation

Create your composer.json file

```json
{
    "autoload": {
        "psr-4": {
            "Obullo\\": "o2/",
            "": "app/classes"
        }
    },
    "require": {
        "obullo/service": "^1.0",
        "relay/relay": "^1.0"
    }
}
```

Update dependencies

```
composer update
composer dump-autoload
```

### Configuration of Vhost File

Put the latest version to your web root (<kbd>/var/www/project/</kbd>). Create your apache vhost file and set your project root as public.

```xml
<VirtualHost *:80>
        ServerName project.example.com

        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/project/public
</VirtualHost>
```

### Configuration of Index.php

When you setup your application you have two options to work with Http middlewares.

#### Setup with Relay ( Default )

It's a simple and lightweight middleware solution.

```php
composer require relay/relay
```

Open your index.php and update <kbd>$app</kbd> variable to Http\Relay;

```php
/*
|--------------------------------------------------------------------------
| Choose your middleware app
|--------------------------------------------------------------------------
*/
$app = new Obullo\Http\Relay\MiddlewarePipe($c);
/*
|--------------------------------------------------------------------------
| Create your http server
|--------------------------------------------------------------------------
*/
$server = Obullo\Http\Server::createServerFromRequest(
    $app,
    $app->getRequest()
);
/*
|--------------------------------------------------------------------------
| Run
|--------------------------------------------------------------------------
*/
$server->listen();
```

Learn more details about <a href="http://relayphp.com/" target="_blank">relay middleware</a>.


#### Setup with Zend Stratigility

It's an advanced middleware solution from zend.

Open your index.php and update <kbd>$app</kbd> variable to Http\Zend\Stratigility\MiddlewarePipe;

```php
/*
|--------------------------------------------------------------------------
| Choose your middleware app
|--------------------------------------------------------------------------
*/
$app = new Obullo\Http\Zend\Stratigility\MiddlewarePipe($c);
```

Learn more details about <a href="https://github.com/zendframework/zend-stratigility" target="_blank">zend middleware</a>.