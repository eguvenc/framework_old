<?php

namespace Service\Provider;

use Obullo\Mongo\Connection;

/**
 * Mongo Provider
 *
 * @category  Provider
 * @package   Mongo
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/providers
 */
Class Mongo implements ProviderInterface
{
    /**
     * Registry
     *
     * @param object $c        container
     * @param array  $commands loader command parameters ( new, return, as .. )
     * 
     * @return void
     */
    public function register($c, $commands = array())
    {
        $c['provider:mongo'] = function ($params = array('db' => 'db')) use ($c, $commands) {
            $mongo  = new Connection($c, $params, $commands);
            return $mongo->connect();
        };
    }
}

// END Mongo class

/* End of file Mongo.php */
/* Location: .classes/Service/Provider/Mongo.php */