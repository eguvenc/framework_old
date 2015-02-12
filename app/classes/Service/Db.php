<?php

namespace Service;

use Obullo\Container\Container;
use Obullo\ServiceProviders\ServiceInterface;

class Db implements ServiceInterface
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
        $c['db'] = function () use ($c) {
            return $c['service provider database']->get(['connection' => 'default']);
        };
    }
}

// END Db service

/* End of file Db.php */
/* Location: .classes/Service/Db.php */