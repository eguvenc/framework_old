<?php

namespace ServiceProvider;

use Obullo\Container\ServiceProvider\AbstractServiceProvider;

class Logger extends AbstractServiceProvider
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
        'logger'
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
        $config    = $this->getConfiguration('logger');

        if (false == $container->get('config')['log']['enabled']) {
            
            $container->share('logger', 'Obullo\Log\NullLogger')
                ->withArgument($container);
            return;
        }

        $logger = $container->share('logger', 'Obullo\Log\Logger')
            ->withArgument($container)
            ->withArgument($container->get('config'))
            ->withArgument($config->getParams());

        foreach ($config->getMethods() as $method) {
            
            $logger->withMethodCall(
                $method['name'],
                $method['argument']
            );
        }
    }
}