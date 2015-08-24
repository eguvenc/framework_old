<?php

namespace Service;

use Obullo\Database\DatabaseManager;
use Obullo\Service\ServiceInterface;
use Obullo\Container\ContainerInterface;

class Db implements ServiceInterface
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
        $c['db'] = function () use ($c) {
            
            $parameters = [
                'provider' => [
                    'name' => 'database',
                    'params' => [
                        'connection' => 'default'
                    ]
                ]
            ];
            $manager = new DatabaseManager($c);
            $manager->setParameters($parameters);
            return $manager->getClass();
        };
    }
}