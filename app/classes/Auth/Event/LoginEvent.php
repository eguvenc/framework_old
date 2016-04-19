<?php

namespace Auth\Event;

use League\Event\Emitter;
use League\Event\AbstractEvent;
use Interop\Container\ContainerInterface as Container;
use League\Container\ImmutableContainerAwareInterface;

class LoginEvent extends AbstractEvent
{
    /**
     * Constructor
     *
     * @param object $container container
     */
    public function __construct(Container $container)
    {
        $emitter = new Emitter;

        if ($emitter instanceof ImmutableContainerAwareInterface) {
            $emitter->setContainer($container);
        }
        $this->setEmitter($emitter);
    }
}