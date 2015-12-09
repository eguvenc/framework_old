<?php

namespace Event;

use League\Event\Emitter;
use League\Event\AbstractEvent;

class LoginEvent extends AbstractEvent
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setEmitter(new Emitter);
    }
}