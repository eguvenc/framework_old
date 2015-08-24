<?php

namespace Service;

use Obullo\Cache\CacheManager;
use Obullo\Service\ServiceInterface;
use Obullo\Container\ContainerInterface;

class Cache implements ServiceInterface
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
        $c['cache'] = function () use ($c) {
            
            $parameters = [
                'provider' => [
                    'name' => 'cache',
                    'params' => [
                        'driver' => 'redis',
                        'connection' => 'default'
                    ]
                ]
            ];
            $manager = new CacheManager($c);
            $manager->setParameters($parameters);
            return $manager->getClass();
        };
    }
}