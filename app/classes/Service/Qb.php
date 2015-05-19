<?php

namespace Service;

use Closure;
use Obullo\Container\Container;
use Obullo\Service\ServiceInterface;
use Obullo\Database\Doctrine\DBAL\QueryBuilder;

class Qb implements ServiceInterface
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
        $c['qb'] = function ($params = array()) use ($c) {
            return new QueryBuilder($c['app']->provider('database')->get($params));
        };
    }
}

// END Qb service

/* End of file Qb.php */
/* Location: .app/classes/Service/Qb.php */