<?php

namespace Service;

use Obullo\Mongo\Db;

/**
 * Mongo Active Record Service
 *
 * @category  Service
 * @package   Mongo
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
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
    public function register($c)
    {
        $c['mongo'] = function () use ($c) {
            return new Db($c, $c->load('service/provider/mongo', 'db'));
        };
    }
}

// END Mongo class

/* End of file Mongo.php */
/* Location: .classes/Service/Mongo.php */