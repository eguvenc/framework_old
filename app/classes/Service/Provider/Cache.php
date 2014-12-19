<?php

namespace Service\Provider;

use Obullo\Cache\Handler\Redis;

/**
 * Cache Provider
 *
 * @category  Provider
 * @package   Cache
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT
 * @link      http://obullo.com/docs/providers
 */
Class Cache implements ProviderInterface
{
    /**
     * Registry
     *
     * @param object $c container
     * 
     * @return void
     */
    public function register($c)
    {
        $c['provider:cache'] = function ($params = array('serializer' => 'SERIALIZER_NONE')) use ($c) {
            return new Redis(
                $c,
                $params['serializer']
            );
        };
    }
}

// END Cache class

/* End of file Cache.php */
/* Location: .classes/Service/Provider/Cache.php */