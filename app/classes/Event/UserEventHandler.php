<?php

namespace Event;

use Obullo\Auth\AuthResult;

/**
 * User event handler
 * 
 * @category  Event
 * @package   UserEventHandler
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/event
 */
Class UserEventHandler
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
     * 
     * @return void
     */
    public function __construct($c)
    {
        $this->c = $c;
    }

    /**
     * Handler user login attempts
     *
     * @param object $authResult AuthResult object
     * 
     * @return void
     */
    public function onLoginAttempt(AuthResult $authResult)
    {
        if ( ! $authResult->isValid()) {

            // Rate limiter class

            $authResult->setCode(-9);
            $authResult->setMessage('Your IP is banned !');

        }
        return $authResult;
    }

    /**
     * Handler user login events
     * 
     * @return void
     */
    public function onAfterLogin()
    {
        // ..
    }

    /**
     * Handle user logout events.
     *
     * @return void
     */
    public function onAfterLogout()
    {
        // ..
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
        $event->listen('login.attempt', 'Event\UserEventHandler.onLoginAttempt');
        $event->listen('after.login', 'Event\UserEventHandler.onAfterLogin');
        $event->listen('after.logout', 'Event\UserEventHandler.onAfterLogout');
    }

}

// END UserEventHandler class

/* End of file UserEventHandler.php */
/* Location: .Event/UserEventHandler.php */