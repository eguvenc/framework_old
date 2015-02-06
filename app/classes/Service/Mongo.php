<?php

namespace Service;

use Obullo\Mongo\Query,
    Obullo\Container\Container,
    Obullo\ServiceProvider\ServiceInterface;

/**
 * Mongo Query Service
 *
 * @category  Service
 * @package   Mongo
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/services
 */
Class Mongo implements ServiceInterface
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
        $c['mongo'] = function () use ($c) {
            return new Query(
                $c,
                [
                    'connection' => 'default',
                ]
            );
        };
    }
}

// END Mongo class

/* End of file Mongo.php */
/* Location: .classes/Service/Mongo.php */