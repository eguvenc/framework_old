<?php

namespace Service;

use Obullo\Container\Container;
use Obullo\Security\Csrf as CsrfClass;
use Obullo\ServiceProviders\ServiceInterface;

class Csrf implements ServiceInterface
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
        $c['csrf'] = function () use ($c) {
            return new CsrfClass($c);
        };
    }
}

// END Csrf service

/* End of file Csrf.php */
/* Location: .classes/Service/Csrf.php */