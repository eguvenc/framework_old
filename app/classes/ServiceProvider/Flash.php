<?php

namespace ServiceProvider;

use Obullo\Container\ServiceProvider\AbstractServiceProvider;

class Flash extends AbstractServiceProvider
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
        'flash'
    ];

    /**
    * Flash Messages
    * 
    * This file contains flash messages configurations.
    * It is used by Flash Class to set messages HTML template or attributes.
    * Array keys are predefined in flash class file.
    *
    * Note : Default CSS classes brought from getbootstrap.com
    * 
    * @return void
    */
    public function register()
    {
        $container = $this->getContainer();

        $container->share('flash', 'Obullo\Flash\Session')
            ->withArgument($container)
            ->withArgument($container->get('session'))
            ->withArgument($container->get('logger'))
            ->withArgument(
                [
                    'notification' => 
                    [
                        'message' => '<div class="{class}">{icon}{message}</div>',
                        'error'  => [
                            'class' => 'alert alert-danger', 
                            'icon' => '<span class="glyphicon glyphicon-remove-sign"></span> '
                        ],
                        'success' => [
                            'class' => 'alert alert-success', 
                            'icon' => '<span class="glyphicon glyphicon-ok-sign"></span> '
                        ],
                        'warning' => [
                            'class' => 'alert alert-warning', 
                            'icon' => '<span class="glyphicon glyphicon-exclamation-sign"></span> '
                        ],
                        'info' => [
                            'class' => 'alert alert-info', 
                            'icon' => '<span class="glyphicon glyphicon-info-sign"></span> '
                        ],
                    ]
                ]
            );
    }
}