<?php

namespace Service\Provider;

use MongoClient,
    MongoCollection;

/**
 * Mongo Provider
 *
 * @category  Provider
 * @package   Mongo
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
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
        $c['provider:mongo'] = function ($db = 'db', $collection = 'test') use ($c) {
            
            $host     = $c->load('config')['nosql']['mongo'][$db]['host'];
            $username = $c->load('config')['nosql']['mongo'][$db]['username'];
            $password = $c->load('config')['nosql']['mongo'][$db]['password'];
            $port     = $c->load('config')['nosql']['mongo'][$db]['port'];
            
            $dsn = "mongodb://$username:$password@$host:$port/$db";

            $mongoClient = new MongoClient($dsn);
            return new MongoCollection($mongoClient->{$db}, $collection);
        };
    }
}

// END Mongo class

/* End of file Mongo.php */
/* Location: .classes/Service/Provider/Mongo.php */