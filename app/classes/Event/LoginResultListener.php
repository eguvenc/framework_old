<?php

namespace Event;

use League\Event\EventInterface as Event;
use League\Event\AbstractListener;

use Obullo\Authentication\AuthResult;
use Obullo\Container\ContainerInterface as Container;

class LoginResultListener extends AbstractListener
{
    /**
     * Container
     * 
     * @var object
     */
    protected $c;

    /**
     * Listen auth credentials
     * 
     * @param Event     $event      EventInterface
     * @param Container $container  ContainerInterfrace
     * @param object    $authResult AuthResult
     * 
     * @return void
     */
    public function handle(Event $event, Container $container = null, AuthResult $authResult = null)
    {
        $this->c = $container;

        if ($authResult->isValid()) {

            $row = $authResult->getResultRow();  // Get query results

        } else {

            // Store attemtps
            // ...
        
            $identifier = $authResult->getIdentifier();

        }
    }

}
