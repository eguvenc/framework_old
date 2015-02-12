<?php

namespace Service;

use Obullo\Container\Container;
use Obullo\ServiceProviders\ServiceInterface;
use Obullo\Flash\Session;

class Flash implements ServiceInterface
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
        $c['flash'] = function () use ($c) {
            return new Session($c);
        };
    }
}

// END Cache service

/* End of file Cache.php */
/* Location: .classes/Service/Queue.php */