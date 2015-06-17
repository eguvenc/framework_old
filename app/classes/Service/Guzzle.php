<?php

namespace Service;

use GuzzleHttp\Client;
use Obullo\Service\ServiceInterface;
use Obullo\Container\ContainerInterface;

class Guzzle implements ServiceInterface
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
        $c['guzzle'] = function () {
            return new Client;
        };
    }
}