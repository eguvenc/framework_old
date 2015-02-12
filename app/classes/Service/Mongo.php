<?php

namespace Service;

use Obullo\Mongo\Query;
use Obullo\Container\Container;
use Obullo\ServiceProviders\ServiceInterface;

class Mongo implements ServiceInterface
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
            return new Query($c, ['connection' => 'default']);
        };
    }
}

// END Mongo service

/* End of file Mongo.php */
/* Location: .classes/Service/Mongo.php */