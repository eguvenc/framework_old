<?php

namespace ServiceProvider;

use Obullo\Container\ServiceProvider\AbstractServiceProvider;

class Form extends AbstractServiceProvider
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
        'form'
    ];

    /**
     * Form Messages
     *
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

        $container->share('form', 'Obullo\Form\Form')
            ->withArgument($container)
            ->withArgument($container->get('request'))
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
                    ],
                    'error' => [
                        'class' => 'has-error has-feedback',
                        'label' => '<label class="control-label" for="%s">%s</label>
                        <span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>'
                    ]
                ]
            );
    }
}