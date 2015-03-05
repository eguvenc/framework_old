<?php

namespace Service;

use Obullo\Container\Container;
use Obullo\ServiceProviders\ServiceInterface;

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
            return $c['service provider cache']->get(['driver' => 'redis', 'options' => array('serializer' => 'none')]);
        };
    }
}

// END Cache service

/* End of file Cache.php */
/* Location: .classes/Service/Cache.php */