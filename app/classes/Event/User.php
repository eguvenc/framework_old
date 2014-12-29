<?php

namespace Event;

use Obullo\Auth\AuthResult,
    Obullo\Auth\User\UserIdentity;

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

            // Store attemtps

        }
        return $authResult;
    }

    /**
     * Invalid auth token event listener
     * 
     * @param object $identity UserIdentity
     * @param string $cookie   user token that we read from cookie
     * 
     * @return void
     */
    public function onInvalidToken(UserIdentity $identity, $cookie)
    {
        $this->c->load('flash/session')->error('Invalid auth token : '.$cookie.' identity '.$identity->getIdentifier().' destroyed');
        $this->c->load('url')->redirect('/login');
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
        $event->listen('auth.token', 'Event\User.onInvalidToken');
        $event->listen('after.logout', 'Event\User.onAfterLogout');
    }

}

// END User class

/* End of file User.php */
/* Location: .Event/User.php */