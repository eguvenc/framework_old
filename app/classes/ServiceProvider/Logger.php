<?php

namespace ServiceProvider;

use Monolog\Logger as Log;
use Monolog\Handler\StreamHandler;
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
        'logger',
        'debugger'
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
        $config    = $container->get('config')->get('config');

        if (false == $config['log']['enabled']) {
            $container->share('logger', 'Obullo\Log\NullLogger');
            return;
        }

        $filename = 'http.log';
        if (defined('STDIN')) {
            $filename = 'cli.log';
        }
        if ($container->get('request')->isAjax()) {
            $filename = 'ajax.log';
        }

        $logger = $container->share('logger', 'Monolog\Logger')
            ->withArgument('system')
            ->withMethodCall(
                'pushHandler',
                [new StreamHandler(DATA .'logs/'.$filename, Log::DEBUG, true, 0666)]
            );

        if ($config['extra']['debugger']) {

            $container->share('debugger', 'Obullo\Debugger\LogHandler');

            $logger->withMethodCall(
                'pushHandler',
                [$container->get('debugger')]
            );
        }
    }
}