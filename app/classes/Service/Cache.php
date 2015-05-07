<?php

namespace Service;

use Obullo\Container\Container;
use Obullo\Service\ServiceInterface;

class Cache implements ServiceInterface
{
    /**
     * Registry
     *
     * @param object $c container
     * 
     * @return void
     */
    public function register(Container $c)
    {
        $c['cache'] = function () use ($c) {
            return $c['app']->provider('cache')->get(['driver' => 'redis', 'connection' => 'default']);
        };
    }
}

// END Cache service

/* End of file Cache.php */
/* Location: .app/classes/Service/Cache.php */