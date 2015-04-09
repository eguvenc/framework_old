<?php

namespace Service;

use Obullo\Container\Container;
use Obullo\ServiceProviders\ServiceInterface;

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
            return $c['service provider query']->get(['connection' => 'default']);
        };
    }
}

// END Query service

/* End of file Query.php */
/* Location: .classes/Service/Query.php */
