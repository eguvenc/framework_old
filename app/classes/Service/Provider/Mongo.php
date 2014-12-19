<?php

namespace Service\Provider;

use Obullo\Mongo\MongoConnection;

/**
 * Mongo Provider
 *
 * @category  Provider
 * @package   Mongo
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT
 * @link      http://obullo.com/docs/providers
 */
Class Mongo implements ProviderInterface
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
        $c['provider:mongo'] = function ($db = 'db') use ($c) {

            $config = $c['config']['nosql']['mongo'][$db];
            $mongo  = new MongoConnection('mongodb://'.$config['username'].':'.$config['password'].'@'.$config['host'].':'.$config['port'].'/'.$db);
            return $mongo->connect();
        };
    }
}

// END Mongo class

/* End of file Mongo.php */
/* Location: .classes/Service/Provider/Mongo.php */