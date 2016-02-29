<?php

namespace ServiceProvider;

use Obullo\Container\ServiceProvider\AbstractServiceProvider;

class Url extends AbstractServiceProvider
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
        'url'
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
        $params    = $this->getConfiguration()->getParams();

        $container->share('url', 'Obullo\Url\Url')
            ->withArgument($container)
            ->withArgument($container->get('logger'))
            ->withArgument($params);
    }
}