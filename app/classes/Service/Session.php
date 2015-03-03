<?php

namespace Service;

use Obullo\Container\Container;
use Obullo\Session\Handler\Cache;
use Obullo\ServiceProviders\ServiceInterface;
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
    public function register(Container $c)
    {
        $c['session'] = function () use ($c) {
            return new SessionClass($c);
        };
    }
}

// END Session service

/* End of file Session.php */
/* Location: .classes/Service/Session.php */