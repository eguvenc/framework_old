<?php

namespace Service;

use Obullo\Container\Container,
    Obullo\ServiceProvider\ServiceInterface,
    Obullo\Cache\Handler\Redis;

/**
 * Cache Service
 *
 * @category  Service
 * @package   Cache
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/container
 */
Class Cache implements ServiceInterface
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
            
            $cache = $this->c->load('service provider cache', ['driver' => 'redis']);
            $cache->setOption(array('serializer' => 'SERIALIZER_PHP'));
            return $cache;
        };
    }
}

// END Cache class

/* End of file Cache.php */
/* Location: .classes/Service/Cache.php */