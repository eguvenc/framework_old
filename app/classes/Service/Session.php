<?php

namespace Service;

use Obullo\Service\ServiceInterface;
use Obullo\Container\ContainerInterface;
use Obullo\Session\Session as SessionClass;

class Session implements ServiceInterface
{
    /**
     * Registry
     *
     * @param object $c container
     * 
     * @return void
     */
    public function register(ContainerInterface $c)
    {
        $c['session'] = function () use ($c) {
            $session = new SessionClass($c);
            $session->registerSaveHandler();   // Handler comes from sesssion config file if we not provide handler object.
            $session->setName();
            $session->start();
            return $session;
        };
    }
}

// END Session service

/* End of file Session.php */
/* Location: .app/classes/Service/Session.php */