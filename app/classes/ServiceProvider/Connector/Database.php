<?php

namespace ServiceProvider\Connector;

use Obullo\Container\ServiceProvider\AbstractServiceProvider;

class Database extends AbstractServiceProvider
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
        'database'
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
        $config    = $this->getConfiguration('database');
        
        $container->share('database', 'Obullo\Container\ServiceProvider\Database')
            ->withArgument($container)
            ->withArgument($config->getParams());


        // DoctrineDBAL Replacement
        // 

        // $container->share('database', 'Obullo\Container\ServiceProvider\DoctrineDBAL')
        //     ->withArgument($container)
        //     ->withArgument($config->getParams());
    }
}