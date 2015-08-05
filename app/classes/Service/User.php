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

            $parameters = [
                'cache.key'     => 'Auth',
                'db.adapter'    => '\Obullo\Authentication\Adapter\Database', // Adapter
                'db.model'      => '\Obullo\Authentication\Model\User',       // User model, you can replace it with your own.
                'db.provider'   => 'database',
                'db.connection' => 'default',
                'db.tablename'  => $params['table'],
            ];
            $manager = new AuthManager($c);
            $manager->setParameters($parameters);

            return $manager;
        };
    }
}