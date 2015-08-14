<?php

namespace Service;

use Obullo\Queue\QueueManager;
use Obullo\Service\ServiceInterface;
use Obullo\Container\ContainerInterface;

class Queue implements ServiceInterface
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
        $c['queue'] = function () use ($c) {

            $parameters = [
                'class' => '\Obullo\Queue\Handler\AMQP',
                'provider' => [
                    'name' => 'amqp',
                    'params' => [
                        'connection' => 'default'
                    ]
                ]
            ];
            $manager = new QueueManager($c);
            $manager->setParameters($parameters);
            $handler = $manager->getHandler();
            return $handler;
        };
    }
}