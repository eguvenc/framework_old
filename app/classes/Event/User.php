<?php

namespace Event;

use Obullo\Container\Container,
    Obullo\Authentication\AuthResult,
    Obullo\Event\EventListenerInterface,
    Obullo\Authentication\User\UserIdentity,
    Obullo\Authentication\Addons\UniqueSessionTrait,
    Obullo\Authentication\Addons\InvalidTokenTrait;

/**
 * User event handler
 * 
 * @category  EventListener
 * @package   User
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/event
 */
Class User implements EventListenerInterface
{
    use UniqueSessionTrait, InvalidTokenTrait;

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
        $event->listen('login.beforeAttempt', 'Event\User.beforeLoginAttempt');
        $event->listen('login.afterAttempt', 'Event\User.afterLoginAttempt');

        $event->listen('auth.unique', 'Event\User.onUniqueSession');
        $event->listen('auth.invalidToken', 'Event\User.onInvalidToken');
    }

}

// END User class

/* End of file User.php */
/* Location: .Event/User.php */