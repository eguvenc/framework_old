<?php

namespace Event\Login;

use Obullo\Authentication\AuthResult;
use Obullo\Container\ContainerInterface as Container;
use Obullo\Container\ContainerAwareInterface;
use Obullo\Event\EventListenerInterface;
use Obullo\Event\EventInterface;

class Attempt implements EventListenerInterface, ContainerAwareInterface
{
    /**
     * Container
     * 
     * @var object
     */
    protected $c;

    /**
     * Injector container
     * 
     * @param ContainerInterface|null $c ContainerInterface
     *
     * @return void
     */
    public function setContainer(Container $c = null)
    {
        $this->c = $c;
    }

    /**
     * Before login attempt
     * 
     * @param array $credentials user login credentials
     * 
     * @return void
     */
    public function before($credentials = array())
    {
        // ..
    }

    /**
     * After login attempts
     *
     * @param object $authResult AuthResult object
     * 
     * @return void
     */
    public function after(AuthResult $authResult)
    {
        if ( ! $authResult->isValid()) {

            // Store attemtps
            // ...
        
            // $row = $authResult->getResultRow();  // Get query results
        }
        return $authResult;
    }

    /**
     * Register the listeners for the subscriber.
     * 
     * @param object $event event class
     * 
     * @return void
     */
    public function subscribe(EventInterface $event)
    {
        $event->listen('login.before', 'Event\Login\Attempt@before');
        $event->listen('login.after', 'Event\Login\Attempt@after');
    }

}