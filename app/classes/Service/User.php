<?php

namespace Service;

use Obullo\Authentication\AuthManager;
use Obullo\Container\ServiceInterface;
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
        $c['user'] = function () use ($c) {

            $parameters = [
                'cache.key' => 'Auth',
                'db.adapter'=> '\Obullo\Authentication\Adapter\Database',
                'db.model'  => '\Obullo\Authentication\Model\Pdo\User',       // User model, you can replace it with your own.
                'db.provider' => [
                    'name' => 'database',
                    'params' => [
                        'connection' => 'default'
                    ]
                ],
                'db.tablename' => 'users',
                'db.id' => 'id',
                'db.identifier' => 'username',
                'db.password' => 'password',
                'db.rememberToken' => 'remember_token',
                'db.select' => [
                    'date',
                ]
            ];
            $manager = new AuthManager($c);
            $manager->setParameters($parameters);
            return $manager;
        };
    }
}