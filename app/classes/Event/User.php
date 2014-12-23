<?php

namespace Event;

use Obullo\Auth\AuthResult;

/**
 * User event handler
 * 
 * @category  Event
 * @package   User
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/event
 */
Class User
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
     * Handle user login attempts
     *
     * @param object $authResult AuthResult object
     * 
     * @return void
     */
    public function onLoginAttempt(AuthResult $authResult)
    {
        if ( ! $authResult->isValid()) {

            // Rate limiter class
            // $authResult->setCode(-9);
            // $authResult->setMessage('Your IP is banned !');

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
        $event->listen('login.attempt', 'Event\User.onLoginAttempt');
        $event->listen('after.login', 'Event\User.onAfterLogin');
        $event->listen('after.logout', 'Event\User.onAfterLogout');
    }

}

// END User class

/* End of file User.php */
/* Location: .Event/User.php */