<?php

namespace Service;

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
            return $c['app']->provider('cache')->get(['driver' => 'redis', 'connection' => 'default']);
        };
    }
}

// END Cache service

/* End of file Cache.php */
/* Location: .app/classes/Service/Cache.php */