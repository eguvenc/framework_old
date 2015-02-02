<?php

namespace Event;

use Obullo\Container\Container,
    Obullo\Authentication\AuthResult,
    Obullo\Authentication\User\UserIdentity;

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
    public function beforeLoginAttempt($credentials = array())
    {
        // ..
    }

    /**
     * Handle user login attempts
     *
     * @param object $authResult AuthResult object
     * 
     * @return void
     */
    public function afterLoginAttempt(AuthResult $authResult)
    {
        if ( ! $authResult->isValid()) {

            // Store attemtps
            // ...
        
            $row = $authResult->getResultRow();  // Get query results

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
        $this->c->load('flash/session')->error(
            sprintf(
                'Invalid auth token : %s identity %s destroyed',
                $cookie,
                $identity->getIdentifier()
            )
        );
        $this->c->load('url')->redirect('/examples/login');
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
     * Unique session handler
     * 
     * @return void
     */
    public function onUniqueSession()
    {
        $sessions = $this->storage->getAllSessions();

        if (sizeof($sessions) < 1) {  // If user have more than one auth session continue to destroy them.
            return;
        }
        $sessionKeys = array();  
        foreach ($sessions as $key => $val) {       // Keep the last session
            $sessionKeys[$val['__time']] = $key;
        }
        $lastSession = max(array_keys($sessionKeys));   // Get the highest integer time
        $protectedSession = $sessionKeys[$lastSession];
        unset($sessions[$protectedSession]);            // Don't touch the current session

        foreach (array_keys($sessions) as $aid) {   // Destroy all other sessions
            $this->storage->killSession($aid);
        }
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
        $event->listen('login.beforeAttempt', 'Event\User.beforeLoginAttempt');
        $event->listen('login.afterAttempt', 'Event\User.afterLoginAttempt');

        $event->listen('auth.invalidToken', 'Event\User.onInvalidToken');
        $event->listen('after.logout', 'Event\User.onAfterLogout');
        $event->listen('auth.unique', 'Event\User.onUniqueSession');
    }

}

// END User class

/* End of file User.php */
/* Location: .Event/User.php */