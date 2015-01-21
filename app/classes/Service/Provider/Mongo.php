<?php

namespace Service\Provider;

use Obullo\Container\Container,
    Obullo\Provider\Mongo,
    Obullo\Provider\ProviderInterface;

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
Class Mongo extends MongoProvider implements ProviderInterface  // Obullo mongo provider a extend olacak bu komutlar onun içinde olacak.
{
    /**
     * Registry
     *
     * @param object $c       Container
     * @param array  $params  parameters
     * @param array  $matches loader commands
     * 
     * @return void
     */
    public function register(Container $c, $params = array(), $matches = array())
    {
        return parent::register($c, $params, $matches);
    }
}

// END Mongo class

/* End of file Mongo.php */
/* Location: .classes/Service/Provider/Mongo.php */