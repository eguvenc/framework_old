<?php

namespace Event\Login;

use Obullo\Container\Container;
use Obullo\Authentication\AuthResult;
use Obullo\Event\EventListenerInterface;

class Attempt implements EventListenerInterface
{
    /**
     * Container
     * 
     * @var object
     */
    protected $c;

    /**
     * Constructor
     *
     * @param object $c container
     */
    public function __construct(Container $c)
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
    public function subscribe($event)
    {
        $event->listen('login.attempt.before', 'Event\Login\Attempt@before');
        $event->listen('login.attempt.after', 'Event\Login\Attempt@after');
    }

}

// END Attempt class

/* End of file Event/Login/Atempt.php */
/* Location: .Event/Login/Atempt.php */