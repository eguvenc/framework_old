<?php

namespace Service;

use Obullo\Container\Container;
use Obullo\Permissions\RbacService;
use Obullo\ServiceProviders\ServiceInterface;

class Rbac implements ServiceInterface
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
        // $c['rbac.db'] = function () use ($c) {
        //     return new PdoServiceProvider();  
        // };
        $c['rbac'] = function () use ($c) {   // $c['service provider rbac']->get('config' => 'default');
            return new RbacService(
                $c, 
                array(
                    'provider' => 'pdo',
                    'connection' => 'db',
                    'driver' => 'mysql'
                )
            );
        };
    }
}

// END Rbac service

/* End of file Rbac.php */
/* Location: .classes/Service/Rbac.php */