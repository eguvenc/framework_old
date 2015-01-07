<?php

namespace Service\Provider;

use Obullo\Cache\Connection;

/**
 * Cache Provider
 *
 * @category  Provider
 * @package   Cache
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/providers
 */
Class Cache implements ProviderInterface
{
    /**
     * Registry
     *
     * @param object $c        container
     * @param array  $commands loader command parameters ( new, as, return ..)
     * 
     * @return void
     */
    public function register($c, $commands = array())
    {
        $c['provider:cache'] = function ($params = array('serializer' => 'SERIALIZER_NONE', 'provider' => 'redis')) use ($c, $commands) {
            $connection = new Connection($c, $params, $commands);
            return $connection->connect();
        };
    }
}

// END Cache class

/* End of file Cache.php */
/* Location: .classes/Service/Provider/Cache.php */