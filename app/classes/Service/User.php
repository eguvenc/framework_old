<?php

namespace Service;

use Obullo\Service\ServiceInterface;
use Obullo\Authentication\AuthManager;
use Obullo\Container\ContainerInterface;

class User implements ServiceInterface
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
        $c['user'] = function ($params = ['table' => 'users']) use ($c) {

            print_r($params);

            $parameters = [
                'cache.key'     => 'Auth',
                'url.login'     => '/membership/login',
                'db.adapter'    => '\Obullo\Authentication\Adapter\Database', // Adapter
                'db.model'      => '\Obullo\Authentication\Model\User',       // User model, you can replace it with your own.
                'db.provider'   => 'database',
                'db.connection' => 'default',
                'db.tablename'  => $params['table'],
            ];
            $manager = new AuthManager($c);
            $manager->setConfiguration($parameters);

            return $manager;
        };
    }
}

// END Authentication service

/* End of file User.php */
/* Location: .app/classes/Service/User.php */