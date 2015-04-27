<?php

namespace Service;

use Obullo\Flash\Session;
use Obullo\Container\Container;
use Obullo\Service\ServiceInterface;

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