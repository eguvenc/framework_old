<?php

namespace Service;

use Obullo\Queue\Handler\AMQP;
use Obullo\Container\Container;
use Obullo\ServiceProviders\ServiceInterface;

class Queue implements ServiceInterface
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
        $c['queue'] = function () use ($c) {
            return new AMQP($c);
        };
    }
}

// END Queue service

/* End of file Cache.php */
/* Location: .classes/queue.php */