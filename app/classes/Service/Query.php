<?php

namespace Service;

use Obullo\Container\Container;
use Obullo\Service\ServiceInterface;

class Query implements ServiceInterface
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
        $c['query'] = function () use ($c) {
            return $c['app']->provider('query')->get(['connection' => 'default']);
        };
    }
}

// END Query service

/* End of file Query.php */
/* Location: .app/classes/Service/Query.php */