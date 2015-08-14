<?php

namespace Service;

use Obullo\Session\SessionManager;
use Obullo\Service\ServiceInterface;
use Obullo\Container\ContainerInterface;

class Session implements ServiceInterface
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
        $c['session'] = function () use ($c) {

            $parameters = [
                'provider' => [
                    'name' => 'cache',
                    'params' => [
                        'driver' => 'redis',
                        'connection' => 'default'
                    ]
                ]
            ];
            $manager = new SessionManager($c);
            $manager->setParameters($parameters);
            $session = $manager->getSession();

            $session->registerSaveHandler('\Obullo\Session\SaveHandler\Cache');
            $session->setName();
            $session->start();
            return $session;
        };
    }
}