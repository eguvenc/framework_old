<?php

namespace ServiceProvider;

use Obullo\Container\ServiceProvider\AbstractServiceProvider;

class Session extends AbstractServiceProvider
{
    /**
     * The provides array is a way to let the container
     * know that a service is provided by this service
     * provider. Every service that is registered via
     * this service provider must have an alias added
     * to this array or it will be ignored.
     *
     * @var array
     */
    protected $provides = [
        'session'
    ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to, but remember, every alias registered
     * within this method must be declared in the `$provides` array.
     *
     * @return void
     */
    public function register()
    {
        $container = $this->getContainer();
        $params    = $this->getConfiguration('session')->getParams();

        $session = $container->share('session', 'Obullo\Session\Session')
            ->withArgument($container->get('cacheManager'))
            ->withArgument($container->get('config'))
            ->withArgument($container->get('request'))
            ->withArgument($container->get('logger'))
            ->withArgument($params);

        $session->withMethodCall('registerSaveHandler', [$params['handler']]);
        $session->withMethodCall('setName', [null]);
        $session->withMethodCall('start');
    }
}