<?php

namespace Service;

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
            return $c['app']->provider('database')->get(['connection' => 'default']);
        };
    }
}