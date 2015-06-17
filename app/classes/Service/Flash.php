<?php

namespace Service;

use Obullo\Flash\Session;
use Obullo\Service\ServiceInterface;
use Obullo\Container\ContainerInterface;

class Flash implements ServiceInterface
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
        $c['flash'] = function () use ($c) {
            return new Session($c);
        };
    }
}