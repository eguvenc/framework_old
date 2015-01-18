<?php

namespace Service;

use Obullo\Mongo\Query;

/**
 * Mongo Query Service ( Shared )
 *
 * @category  Service
 * @package   Mongo
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/services
 */
Class MongoQ implements ServiceInterface
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
        $c['mongoQ'] = function ($database) use ($c) {
            return new Query($c, $database, $database->provider->getName());
        };
    }
}

// END MongoQuery class

/* End of file MongoQuery.php */
/* Location: .classes/Service/MongoQuery.php */