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

// END Flash service

/* End of file Flash.php */
/* Location: .app/classes/Service/Flash.php */