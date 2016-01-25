<?php

namespace Event;

use League\Event\AbstractListener;
use League\Event\EventInterface as Event;

use Obullo\Authentication\AuthResult;
use League\Container\ImmutableContainerAwareTrait;
use League\Container\ImmutableContainerAwareInterface;

class LoginResultListener extends AbstractListener implements ImmutableContainerAwareInterface
{
    use ImmutableContainerAwareTrait;

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
