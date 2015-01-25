<?php

namespace Service;

use Obullo\Container\Container,
    Obullo\Cache\Handler\Redis;

/**
 * Cache Service ( Shared )
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
            
            // https://github.com/CHH/cache-service-provider

            // config dosyasından gelicek bağlantılar.

            return new CacheServiceProvider($c, array(
                'cache.options' => array(
                    "default" => array(
                        "driver" => "redis"
                    )
                )
            );

            // return new Redis($c);
        };
    }
}

// END Cache class

/* End of file Cache.php */
/* Location: .classes/Service/Cache.php */