<?php

namespace Auth\Event;

use League\Event\AbstractListener;
use League\Event\EventInterface as Event;

use Obullo\Authentication\AuthResult;
use Obullo\Container\ContainerAwareTrait;
use Obullo\Container\ContainerAwareInterface;

class LoginResultListener extends AbstractListener implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Listen auth credentials
     * 
     * @param Event  $event      EventInterface
     * @param object $authResult AuthResult
     * 
     * @return void
     */
    public function handle(Event $event, AuthResult $authResult = null)
    {
        $container = $this->getContainer();

        if ($authResult->isValid()) {

            $row = $authResult->getResultRow();  // Get query results

        } else {

            // Store attemtps
            // ...
        
            $identifier = $authResult->getIdentifier();

        }
    }

}
