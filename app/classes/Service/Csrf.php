<?php

namespace Service;

use Obullo\Service\ServiceInterface;
use Obullo\Security\Csrf as CsrfClass;
use Obullo\Container\ContainerInterface;

class Csrf implements ServiceInterface
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
        $c['csrf'] = function () use ($c) {
            return new CsrfClass($c);
        };
    }
}