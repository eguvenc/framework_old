<?php

namespace Service\Provider;

use Obullo\Database\Connection;

/**
 * Db Provider
 *
 * @category  Provider
 * @package   Db
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/providers
 */
Class Db implements ProviderInterface
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
        $c['provider:db'] = function ($params = array('db' => 'db', 'provider' => 'mysql')) use ($c, $commands) {
            $connection = new Connection($c, $params, $commands);
            return $connection->connect();
        };
    }
}

// END Db class

/* End of file Db.php */
/* Location: .classes/Service/Provider/Db.php */